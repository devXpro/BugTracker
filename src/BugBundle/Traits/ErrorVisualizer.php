<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 10.07.15
 * Time: 12:54
 */

namespace BugBundle\Traits;


trait ErrorVisualizer
{
    public function renderError($alias){
        return $this->render('@Bug/Messages/error.html.twig', array('message' => $this->get('translator')->trans($alias)));
    }
}