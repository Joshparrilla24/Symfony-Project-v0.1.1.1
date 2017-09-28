<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Form\FileUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;


class UploadController extends Controller
{
    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $formfile = new File();
        $creationdate = new DateTime();

        $form = $this->createForm(FileUploadType::class, $formfile);
        $formfile->setUser($this->getUser());
        $form ->handleRequest($request);

        if($request->isMethod('POST')){
        if($form->isSubmitted() && $form->isValid()) {
            //upload files
            $file = $formfile->getBioFile();

            $formfile->setName($file);
            $formfile->setFilemimetype($file);
            $formfile->setFilepath($file);
            $formfile->setFilesize($file);
            $formfile->setCreated($creationdate);

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
