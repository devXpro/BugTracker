<?php

namespace BugBundle\Traits;

trait ErrorVisualizer
{
    public function renderError($alias)
    {
        return $this->render(
            '@Bug/Messages/error.html.twig',
            array('message' => $this->get('translator')->trans($alias))
        );
    }
}
