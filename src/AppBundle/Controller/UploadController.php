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
        $creationdate = new \DateTime();

        $form = $this->createForm(FileUploadType::class, $formfile);
        $formfile->setUser($this->getUser());

        $form ->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            //upload files and store in db
            $file = $formfile->getBioFile();

            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $mimeType = $file->getClientOriginalExtension();
            $filepath = $this->getParameter('File_Directory').'/'.$filename;
            $filesize = $file->getClientSize();


            $formfile->setName($filename);
            $formfile->setFilemimetype($mimeType);
            $formfile->setFilepath($filepath);
            $formfile->setFilesize($filesize);
            $formfile->setCreated($creationdate);
            $formfile->setUpdated($creationdate);

            $em -> persist($formfile);
            $em -> flush();


            $file->move(

                $this->getParameter('File_Directory'),
                $filename

            );



            return $this->redirectToRoute('homepage');
        }


        return $this->render('AppBundle:Upload:upload.html.twig', array(
            // ...
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ));
    }

}
