<?php

namespace BugBundle\Tests\Unit\Services;

use BugBundle\Services\TransHelper;
use Symfony\Component\Translation\TranslatorInterface;

class TransHelperTest extends \PHPUnit_Framework_TestCase
{
    /** @var  TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $translator;

    /** @var  TransHelper */
    protected $transHelper;

    protected function setUp()
    {
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->transHelper = new TransHelper($this->translator);
    }

    /**
     * @dataProvider transUpDataProvider
     * @param $string
     * @param $transUp
     */
    public function testTransUp($string, $transUp)
    {
        $this->translator->expects($this->once())->method('trans')->will($this->returnArgument(0));
        $result = $this->transHelper->transUp($string);
        $this->assertEquals($transUp, $result);
    }

    public function transUpDataProvider()
    {
        return [
            ['яблоко', 'Яблоко'],
        ];
    }
}
