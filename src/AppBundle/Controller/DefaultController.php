<?php
namespace AppBundle\Controller;
use AppBundle\Entity\User;
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
            throw $this->createAccessDeniedException('You cannot access this page!');
        }

        $em = $this->getDoctrine()->getManager();

        $userdata = $this->getDoctrine()
               ->getRepository(User::class)
                ->findAll();





        return $this->render('admin.html.twig',[
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'user' => $user,
            'userlist' => $userdata,


        ]);

    }

}
