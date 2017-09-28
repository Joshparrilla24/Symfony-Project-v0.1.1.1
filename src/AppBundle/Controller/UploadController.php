<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Form\FileUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;


class UploadController extends Controller
{
    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction(Request $request)
    {


        $formfile = new File();
        $form = $this->createForm(FileUploadType::class, $formfile);
        $formfile->setUser($this->getUser());
        $form ->handleRequest($request);

        if($request->isMethod('POST')){
        if($form->isSubmitted() && $form->isValid()) {

            $file = $formfile->getBioFile();

            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(

                $this->getParameter('File_Directory'),
                $filename

            );

            $formfile->setName($file);

            return $this->redirectToRoute('homepage');
        }

        }

        return $this->render('AppBundle:Upload:upload.html.twig', array(
            // ...
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ));
    }

}
