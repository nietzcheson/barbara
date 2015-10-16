<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoursesController extends Controller
{
    public function indexAction()
    {
        $coursesManager = $this->get('moocsy.courses_manager');

        $courses = $coursesManager->findAll();

        return $this->render('MoocsyBundle:Courses:courses.html.twig', array('courses' => $courses));
    }

    public function newAction(Request $request)
    {
        $coursesManager = $this->get('moocsy.courses_manager');
        $courses = $coursesManager->create();

        $coursesForm = $this->createForm('moocsy_courses_type', $courses)->handleRequest($request);

        if($coursesForm->isValid()){
            $courses->upload();
            $coursesManager->save($courses);

            $this->get('artesanus.flashers')->add('info','Se ha creado un nuevo curso');

            return $this->redirect($this->generateUrl('moocsy_admin_course', array('course' => $courses->getSlug())));

        }

        return $this->render('MoocsyBundle:Courses:courses-new.html.twig', array(
            'courses_form' => $coursesForm->createView()
        ));
    }

    public function editAction($course, Request $request)
    {
        $coursesManager = $this->get('moocsy.courses_manager');
        $course = $coursesManager->findOneBySlug($course);

        $coursesForm = $this->createForm('moocsy_courses_type', $course)->handleRequest($request);

        $coursesUsersOriginales = $coursesManager->coursesUsersOriginales($course);

        $courseAttachmentsOriginals = $coursesManager->courseAttachmentsOriginals($course);

        if($coursesForm->isValid()){

            $course->upload();

            $course->getCoursesCovers()->setCourses($course);
            $course->getCoursesCovers()->upload();

            $coursesManager->update($course);
            $this->get('artesanus.flashers')->add('info','El Curso se ha modificado');
            return $this->redirect($this->generateUrl('moocsy_admin_course', array('course' => $course->getSlug())));
        }

        $courseUserForm = $this->createForm('moocsy_course_user_type', $course)->handleRequest($request);

        if($courseUserForm->isValid()){

            foreach($course->getCoursesUsers() as $opcion){
                $opcion->setCourses($course);
            }

            $coursesManager->updateCoursesUsers($course, $coursesUsersOriginales);
            $this->get('artesanus.flashers')->add('info','Se han actualizado los datos de los usuarios del Curso');
            return $this->redirect($this->generateUrl('moocsy_admin_course', array('course' => $course->getSlug())));
        }

        $courseAttachmentsForm = $this->createForm('moocsy_course_attachments_type', $course)->handleRequest($request);

        if($courseAttachmentsForm->isValid()){

            foreach($course->getAttachments() as $attachment){
                $attachment->upload();
                $attachment->setCourses($course);
            }

            $coursesManager->updateCourseAttachment($course, $courseAttachmentsOriginals);
            $this->get('artesanus.flashers')->add('info','Se han actualizado los datos de los usuarios del Curso');
            return $this->redirect($this->generateUrl('moocsy_admin_course', array('course' => $course->getSlug())));
        }

        return $this->render('MoocsyBundle:Courses:course.html.twig', array(
            'course'                    => $course,
            'courses_form'              => $coursesForm->createView(),
            'course_user_form'          => $courseUserForm->createView(),
            'course_attachment_form'    => $courseAttachmentsForm->createView()
        ));
    }

    public function frontAction($course, Request $request)
    {
        $courseManager = $this->get('moocsy.courses_manager');

        $course = $courseManager->findOneBySlug($course);

        $courseUser = $courseManager->findCourseUser($course, $this->getUser());

        if(null === $courseUser){
            $this->get('artesanus.flashers')->add('warning','El curso al que estás tratando de acceder no existe');
            return $this->redirect($this->generateUrl('artesanus_front_user_profile'));
        }

        $interval = $courseManager->interval($courseUser);

        $modulesReleased = array();

        if(is_int($interval)){
            $modulesReleased = $courseManager->modulesReleased($courseUser);
        }

        $usernameCertificateForm = $this->createForm('barbara_username_certificate_type', $courseUser)->handleRequest($request);

        if($usernameCertificateForm->isValid()){
            $courseUserManager = $this->get('moocsy.courses_users_manager');
            $courseUser->setCertified(new \Datetime('now'));
            $courseUserManager->save($courseUser);

            $this->get('artesanus.flashers')->add('info','Has grabado tu nombre para tu certificado');
            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug() )));
        }

        return $this->render('MoocsyBundle:Front:courses.html.twig', array(
            'course'                    => $courseUser,
            'modules_released'          => $modulesReleased,
            'username_certificate_form' => $usernameCertificateForm->createView()
        ));
    }

    public function deleteAction($course, Request $request)
    {
        $coursesManager = $this->get('moocsy.courses_manager');
        $course = $coursesManager->findOneBySlug($course);

        $coursesManager->delete($course);

        $this->get('artesanus.flashers')->add('warning','Se ha eliminado un curso');
        return $this->redirect($this->generateUrl('moocsy_admin_courses'));
    }

    public function coursesProfileAction()
    {
        if(!$this->isGranted("IS_AUTHENTICATED_FULLY")){
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $coursesManager = $this->get('moocsy.courses_manager');
        $courses = $coursesManager->findCoursesUser();

        return $this->render('MoocsyBundle:Profile:courses.html.twig', array(
            'courses' => $courses
        ));
    }

    public function certificateDownloadAction($course)
    {
        $coursesManager = $this->get('moocsy.courses_manager');
        $course = $coursesManager->findOneBySlug($course);

        $courseUser = $coursesManager->findCourseUser($course, $this->getUser());

        require_once($this->get('kernel')->getRootDir().'/config/dompdf_config.inc.php');

        $html = $this->renderView('AppBundle::Courses/certificate.html.twig', array(
            'nombre' => $courseUser->getUsernameCertificate(),
            'fecha'  => $courseUser->getCertified(),
            'curso'  => $courseUser->getCourses()->getCourse()
        ));

        //return $html;
        $dompdf = new \DOMPDF();
        $dompdf->set_paper('a4', 'landscape');
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream($courseUser->getCourses()->getCourse().".pdf");
    }

    public function copyAction($course)
    {
        /**
         * Create Course
         */
        $coursesManager = $this->get('moocsy.courses_manager');
        $course = $coursesManager->findOneBySlug($course);

        $newCourse = $coursesManager->create();

        //$quantityCourses = count($coursesManager->findAll()) + 1;

        $newCourse->setCourse($course->getCourse());
        $newCourse->setDescription($course->getDescription());
        $newCourse->setTemporality($course->getTemporality());
        $newCourse->setEnabled($course->getEnabled());
        $newCourse->setPublished($course->getPublished());
        $newCourse->setSKU($course->getSKU());
        $newCourse->setPath($course->getPath());

        /**
         * Persist Course
         */

        $coursesManager->save($newCourse);

        if($course->getCoursesCovers()){

            /**
             * Create CourseCovers
             */

            $coursesCoversManager = $this->get('moocsy.courses_covers_manager');
            $coursesCovers = $coursesCoversManager->create();

            $coursesCovers->setCourses($newCourse);
            $coursesCovers->setPath($course->getCoursesCovers()->getPath());

            /**
             * Persist CourseCovers
             */

            $coursesCoversManager->save($coursesCovers);
        }

        if($course->getAttachments()){

            foreach($course->getAttachments() as $attachment){

                /**
                 * Create CourseAttachments
                 */

                $coursesAttachmentsManager = $this->get('moocsy.courses_attachments_manager');
                $coursesAttachments = $coursesAttachmentsManager->create();

                $coursesAttachments->setCourses($newCourse);
                $coursesAttachments->setAttachment($attachment->getAttachment());
                $coursesAttachments->setPath($attachment->getPath());

                /**
                 * Persist CourseCovers
                 */

                $coursesAttachmentsManager->save($coursesAttachments);
            }

        }

        /**
         * Create Course Modules
         */

        foreach($course->getModules() as $module){

            $modulesManager = $this->get('moocsy.modules_manager');
            $modules = $modulesManager->create();

            $modules->setCourses($newCourse);
            $modules->setModule($module->getModule());
            $modules->setDescription($module->getDescription());

            $modulesManager->save($modules);

            foreach($module->getItems() as $item){

                $itemsManager = $this->get('moocsy.items_manager');
                $items = $itemsManager->create();

                $items->setModules($modules);
                $items->setItem($item->getItem());
                $items->setItemsType($item->getItemsType());
                $items->setDescription($item->getDescription());



                $itemsManager->save($items);


                switch ($item->getItemsType()) {
                    case 'items_audio':

                        if($item->getItemsAudio()){
                            $itemsAudioManager = $this->get('moocsy.items_audio_manager');

                            $itemsAudio = $itemsAudioManager->create();

                            $itemsAudio->setItems($items);
                            $itemsAudio->setAudio($item->getItemsAudio()->getAudio());

                            $itemsAudioManager->save($itemsAudio);
                        }

                        break;
                    case 'items_audio_down':

                        if($item->getItemsAudioDown()){

                            $itemsAudioDownManager = $this->get('moocsy.items_audio_down_manager');

                            $itemsAudioDown = $itemsAudioDownManager->create();

                            $itemsAudioDown->setItems($items);
                            $itemsAudioDown->setAudio($item->getItemsAudioDown()->getAudio());

                            $itemsAudioDownManager->save($itemsAudioDown);
                        }

                        break;
                    case 'items_file':

                        if($item->getItemsFile()){

                            $itemsFileManager = $this->get('moocsy.items_file_manager');

                            $itemsFile = $itemsFileManager->create();

                            $itemsFile->setItems($items);
                            $itemsFile->setPath($item->getItemsFile()->getPath());

                            $itemsFileManager->save($itemsFile);
                        }

                        break;
                    case 'items_forum':

                        if($item->getItemsForum()){

                            $itemsForumManager = $this->get('moocsy.items_forum_manager');

                            $itemsForum = $itemsForumManager->create();

                            $itemsForum->setItems($items);
                            $itemsForum->setForum($item->getItemsForum()->getForum());

                            $itemsForumManager->save($itemsForum);
                        }

                        break;
                    case 'items_video':

                        if($item->getItemsVideo()){

                            $itemsVideoManager = $this->get('moocsy.items_video_manager');

                            $itemsVideo = $itemsVideoManager->create();

                            $itemsVideo->setItems($items);
                            $itemsVideo->setVideo($item->getItemsVideo()->getVideo());

                            $itemsVideoManager->save($itemsVideo);
                        }

                        break;
                    case 'items_quiz':

                        if($item->getItemsQuiz()){

                            $itemsQuizManager = $this->get('moocsy.items_quiz_manager');

                            $itemsQuiz = $itemsQuizManager->create();

                            $itemsQuiz->setItems($items);
                            $itemsQuiz->setQuiz($item->getItemsQuiz()->getQuiz());

                            $itemsQuizManager->save($itemsQuiz);

                            if($item->getItemsQuiz()->getQuestions()){

                                foreach($item->getItemsQuiz()->getQuestions() as $question){

                                    $questionsManager = $this->get('moocsy.questions_manager');
                                    $questions = $questionsManager->create();

                                    $questions->setItemsQuiz($itemsQuiz);
                                    $questions->setQuestion($question->getQuestion());

                                    $questionsManager->save($questions);
                                }
                            }
                        }
                        break;
                    default:
                        break;
                }

            }

        }


        return $this->redirect($this->generateUrl('moocsy_admin_courses'));
    }
}
