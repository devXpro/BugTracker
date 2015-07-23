<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 09.07.15
 * Time: 19:48
 */

namespace BugBundle\Security;


use BugBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BugAbstractVoter implements VoterInterface
{

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, $this->getSupportedAttributes());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        foreach ($this->getSupportedClasses() as $supportedClass) {
            if ($supportedClass === $class || is_subclass_of($class, $supportedClass)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return an array of supported classes. This will be called by supportsClass
     *
     * @return array an array of supported classes, i.e. array('Acme\DemoBundle\Model\Product')
     */
    abstract protected function getSupportedClasses();

    /**
     * Return an array of supported attributes. This will be called by supportsAttribute
     *
     * @return array an array of supported attributes, i.e. array('CREATE', 'READ')
     */
    abstract protected function getSupportedAttributes();

    protected function checkSupportedInAttributes(array $atributesToCheck)
    {
        foreach ($atributesToCheck as $attr) {
            if (in_array($attr, $this->getSupportedAttributes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TokenInterface $token
     * @param null|object $obj
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $obj, array $attributes)
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$this->checkSupportedInAttributes($attributes)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        return $this->decide($user, $obj, $attributes);

    }

    abstract protected function decide(User $user, $obj, array $attributes);


}
