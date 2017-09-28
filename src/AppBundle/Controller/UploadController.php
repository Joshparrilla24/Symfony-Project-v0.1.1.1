<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UploadController extends Controller
{
    /**
     * @Route("/upload")
     */
    public function uploadAction()
    {
        return $this->render('AppBundle:Upload:upload.html.twig', array(
            // ...
        ));
    }

}
