<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Comment;
use App\Entity\Conference;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    /**
     * @var EncoderFactoryInterface $encoderFactory
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $amsterdam = new City();
        $amsterdam->setName('Amsterdam');
        $amsterdam->setPosition(1);
        $amsterdam->setVisible(true);
        $manager->persist($amsterdam);

        $paris = new City();
        $paris->setName('Paris');
        $paris->setPosition(2);
        $paris->setVisible(true);
        $manager->persist($paris);

        $amsterdamConference = new Conference();
        $amsterdamConference->setCity($amsterdam);
        $amsterdamConference->setTitle('Title1');
        $amsterdamConference->setBody('Body1');
        $amsterdamConference->setDate(new \DateTime('2020-01-01 18:00:00'));
        $amsterdamConference->setVisible(true);
        $manager->persist($amsterdamConference);

        $parisConference = new Conference();
        $parisConference->setCity($paris);
        $parisConference->setTitle('Title2');
        $parisConference->setBody('Body2');
        $parisConference->setDate(new \DateTime('2021-01-01 18:00:00'));
        $parisConference->setVisible(true);
        $manager->persist($parisConference);

        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setCity($amsterdam);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword('admin', null));
        $admin->setActive(true);
        $manager->persist($admin);

        $author = new User();
        $author->setFirstName('User');
        $author->setLastName('User');
        $author->setCity($amsterdam);
        $author->setRoles(['ROLE_USER']);
        $author->setUsername('testuser');
        $author->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword('11111111', null));
        $author->setActive(true);
        $manager->persist($author);

        $comment1 = new Comment();
        $comment1->setAuthor($author);
        $comment1->setConference($amsterdamConference);
        $comment1->setText('This was a great conference.');
        $comment1->setVisible(true);
        $manager->persist($comment1);

        $manager->flush();
    }
}
