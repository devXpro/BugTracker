<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 03.07.15
 * Time: 12:12
 */

namespace BugBundle\Services;

use Symfony\Component\Translation\TranslatorInterface;

class TransHelper
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans=$translator;
    }

    public function transUp($string)
    {
        $translator = $this->trans;
        $string = $translator->trans($string);
        $encoding = 'UTF8';
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    public function transLo($string){
        $translator = $this->trans;
        $string = $translator->trans($string);
        return $string;
    }


}