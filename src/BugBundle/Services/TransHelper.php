<?php

namespace BugBundle\Services;

use Symfony\Component\Translation\TranslatorInterface;

class TransHelper
{
    /** @var TranslatorInterface */
    private $trans;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    /**
     * @param $string
     * @return string
     */
    public function transUp($string)
    {
        $translator = $this->trans;
        $string = $translator->trans($string);
        $encoding = 'UTF8';
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding).$then;
    }
}
