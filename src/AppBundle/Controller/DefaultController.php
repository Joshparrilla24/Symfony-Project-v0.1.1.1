<?php
namespace AppBundle\Controller;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\File;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $files = $this->getUser()->getFiles();

        if($user->getUserrole() == 'admin')
        {

            return $this->redirectToRoute('admin-home') ;

        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'user' => $user,
            'files' => $files

        ]);


    }

    /**
     * @Route("/admin", name="admin-home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->getUserrole() != "admin"){

            return $this->render('notfound.html.twig', [

                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR

            ]);
           // throw $this->createAccessDeniedException('You cannot access this page!');
        }

        $em = $this->getDoctrine()->getManager();

        $userdata = $this->getDoctrine()
               ->getRepository(User::class)
                ->findBy([

                    'userrole' => 'member'

                ]);





        return $this->render('admin.html.twig',[

            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'user' => $user,
            'userlist' => $userdata,


        ]);

    }

    /**
     *
     * @Route("/admin/{id}", name="edit-user-info")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUserInfoAction(Request $request, $id)
    {

        //query to search the id passed from admin page
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);


        //error handler
        if (!$user) {

            throw $this->createNotFoundException(

                'No user found for id '.$id
            );
        }

        //builds form to edit user values
        $form = $this->createForm(UserEditType::class, $user);


        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {

            //update db
            if($user){

                $username = $user->getUsername();
                $email = $user->getEmail();
                $role = $user->getUserrole();

                $user->setUsername($username);
                $user->setEmail($email);
                $user->setUserrole($role);

                $em->persist($user);
                $em->flush();



            }

            return $this->redirectToRoute('admin-home');



        }
        return $this->render('AppBundle:Update:updateusers.html.twig', array(
            // ...
            'form' => $form->createView(),
            'user' => $this->getUser(),


        ));
    }

    /**
     *
     * @Route("/userdelete/{id}", name="delete-user")
     * @param $id
     */
    public function deleteUserAction($id)

    {

        //find passed id in db
        //find id in db
        $em = $this->getDoctrine()->getManager();

        $userid = $this->getDoctrine()

                ->getRepository(User::class)
                ->find($id);

        if(!$userid){

            throw $this->createNotFoundException(

                'No file found for id '.$id

            );

        }

        $em ->remove($userid);
        $em ->flush();



        return $this->redirectToRoute('admin-home');




    }


}
