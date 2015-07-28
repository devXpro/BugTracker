<?php
namespace BugBundle\DataFixtures\ORM;

use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadRolesAndUsers
 * @package BugBundle\DataFixtures\ORM
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class LoadRolesAndUsers implements FixtureInterface, ContainerAwareInterface
{

    private $names = array(
        'Noah',
        'Emma',
        'Ethan',
        'Oliver',
        'Evelyn',
        'Evelyn',
        'Madison',
        'Emily',
        'Hannah',
        'Zoe',
        'Mason',
        'Elizabeth',
        'Jack',
        'Ella',
        'Jacob',
        'Madison',
        'Oliver',
        'Avery',
        'Jackson',
        'Charlotte',
        'Logan',
        'Alexander',
        'Addison',
        'Aria',
        'Natalie',
    );
    private $lastName = array(
        'Moore',
        'Anderson',
        'Taylor',
        'Thomas',
        'White',
        'Harris',
        'Garcia',
        'Robinson',
        'Lee',
        'Hall',
        'Allen',
        'Martin',
        'Rodriguez',
        'Lewis',
        'Clark',
        'Martinez',
        'Walker',
    );
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $roles = array(Role::ROLE_ADMIN, Role::ROLE_USER, Role::ROLE_MANAGER);
        $indents = $this->makeIdentities();
        $i = 0;
        foreach ($roles as $roleName) {
            if (!$role = $manager->getRepository('BugBundle:Role')->findOneBy(array('role' => $roleName))) {
                $role = new Role();
            }
            $role->setRole($roleName);
            //add User (User name is a role user)
            $userName = strtolower(str_replace('ROLE_', '', $roleName));
            $role->setAlias($userName);
            if (!$user = $manager->getRepository('BugBundle:User')->findOneBy(array('username' => $userName))) {
                $user = new User();
            }
            $user->addRole($role);
            $names = $indents[$i];
            unset($indents[$i]);
            $i++;
            $user->setFullName($names['full']);
            $user->setUsername($userName);
            $user->setEmail($userName.'@ukr.net');
            $user->setPassword($userName);//User name and password is a same
            $user = $this->container->get('bug.userManager')->encodePassword($user);
            $manager->persist($role);
            $manager->persist($user);
        }
        $manager->flush();

        foreach ($roles as &$role) {
            $role = $manager->getRepository('BugBundle:Role')->findOneBy(array('role' => $role));
        }
        foreach ($indents as $names) {
            $user = new User();
            $role = $roles[rand(0, 2)];
            $user->addRole($role);
            $user->setUsername($names['login']);
            $user->setFullName($names['full']);
            $user->setEmail($names['login'].'@gmail.com');
            $user->setPassword($names['login']);
            $user = $this->container->get('bug.userManager')->encodePassword($user);
            $manager->persist($user);

        }
        $manager->flush();

        $persist = function (array $labels, $name, $add = false) use ($manager) {
            $name = 'BugBundle\Entity\\'.$name;
            foreach ($labels as $label) {
                $entity = new $name();
                $entity->setLabel($label);
                if ($add && in_array($label, array(IssueStatus::OPEN, IssueStatus::REOPEN,))) {
                    $entity->setOpen(true);
                }
                $manager->persist($entity);

            }
        };

        $persist(
            [
                IssueStatus::OPEN,
                IssueStatus::REOPEN,
                'Needs Work',
                'Needs Review',
                'Done',
                'Patch',
                'Fixed',
                'Postponed',
                'Closed',
            ],
            'IssueStatus',
            true
        );
        $persist(
            ['Fixed', 'Duplicate', 'Won\'t fix', 'Incomplete', 'Cannot reproduce', 'Redundant', 'Invalid'],
            'IssueResolution'
        );
        $persist(['Highest', 'High', 'Medium', 'Low', 'Lowest'], 'IssuePriority');
        $manager->flush();

        return true;
    }


    private function makeIdentities()
    {
        $res = array();
        for ($i = 1; $i < 15; $i++) {
            $name = $this->names[rand(0, count($this->names) - 1)];
            $last = $this->lastName[rand(0, count($this->lastName) - 1)];
            $ident = array('full' => $name.' '.$last, 'login' => strtolower($name.'_'.$last));
            if (!in_array($ident, $res)) {
                $res[] = $ident;
            }
        }

        return $res;
    }
}
