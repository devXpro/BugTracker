<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use BugBundle\Form\Type\ProjectType;
use BugBundle\Tests\BugTypeTestCase;
use BugBundle\Tests\EntityTypeStub;
use Symfony\Component\Form\PreloadedExtension;

class ProjectTypeTest extends BugTypeTestCase
{
    /** @var  User [] */
    private $members;


    /**
     * @dataProvider formDataProvider
     * @param Project $project
     * @param $formData
     * @param User $user
     */
    public function testSubmitValidData(Project $project, $formData, User $user)
    {
        $projectType = new ProjectType($this->getTokenStorageWithUserMock($user));
        $form = $this->factory->create($projectType);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($project, $form->getData());

    }

    public function formDataProvider()
    {
        /** @var User $user */
        $user = $this->getEntity('BugBundle\Entity\User', array('username'));
        $project = new Project();
        $members = $this->getEntitySet('BugBundle\Entity\User', array('username'));
        array_walk(
            $members,
            function ($member) use ($project) {
                $project->addMember($member);
            }
        );
        $project->setCode('EEE');
        $project->setCreator($user);
        $project->setLabel('MegaProject');
        $project->setSummary('MegaProject MegaProjectMegaProject MegaProjectMegaProject MegaProject');
        $this->members = $members;
        $this->members[$user->getId()] = $user;
        $formData = $this->entityToFormData($project);

        return [
            [
                $project,
                $formData,
                $user,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $this->formDataProvider();
        $creatorStub = new EntityTypeStub($this->members, 'bug_select_user');
        $membersStub = new EntityTypeStub($this->members, 'bug_select_users', array('multiple' => true));
        $stubs = array(
            $creatorStub->getName() => $creatorStub,
            $membersStub->getName() => $membersStub,
        );

        return array(new PreloadedExtension($stubs, array()));
    }
}
