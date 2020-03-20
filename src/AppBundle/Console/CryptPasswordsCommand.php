<?php

namespace AppBundle\Console;

use AppBundle\Entity\Contact;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Crypt all passwords from contact entity using bcrypt.
 */
class CryptPasswordsCommand extends Command
{
    protected static $defaultName = 'app:contact:crypt-passwords';

    protected $managerRegistry;
    protected $encoder;

    public function __construct(ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder, $name = null)
    {
        $this->managerRegistry = $managerRegistry;
        $this->encoder = $encoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Crypt all passwords from contact entity using bcrypt.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contacts = $this->managerRegistry->getRepository(Contact::class)->findAll();

        /** @var Contact $contact */
        foreach ($contacts as $contact) {
            if (!empty($contact->getPassword())) {
                $passwordEncoded = $this->encoder->encodePassword($contact, $contact->getPassword());
                $contact->setPassword($passwordEncoded);
            }
        }

        $this->managerRegistry->getManager()->flush();
        $output->writeln('done.');

        return 0;
    }
}
