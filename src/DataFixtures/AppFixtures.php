<?php

/**
 *
 * Pour installer le bundle de fixture ET faker
 * composer require orm-fixtures --dev
 * composer require fakerphp/faker
 *
 * Pour exécuter ces fixtures :
 * php bin/console doctrine:fixtures:load
 *
 */


namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Entity\OutingStatus;
use App\Entity\Place;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    //cette méthode est appelée quand on exécute la commande php bin/console d:f:l
    //on reçoit un entitymanager en argument \o/
    public function load(ObjectManager $manager)
    {
        //nous permet de générer des données bidons
        //voir ici pour tout ce qu'on peut générer :
        //https://fakerphp.github.io/formatters/numbers-and-strings/
        $faker = \Faker\Factory::create("en_EN");

        //INSERTION DES CAMPUS
        $campus = new Campus();
        $campus->setName('MONTREAL');
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setName('VANCOUVER');
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setName('OTTAWA');
        $manager->persist($campus);

        $manager->flush();
        $allCampus = $manager->getRepository(Campus::class)->findAll();

        //INSERTION DES OUTINGSTATUS
        $status = new OutingStatus();
        $status->setDescription('Created');
        $manager->persist($status);

        $status = new OutingStatus();
        $status->setDescription('Opened');
        $manager->persist($status);

        $status = new OutingStatus();
        $status->setDescription('Closed');
        $manager->persist($status);

        $status = new OutingStatus();
        $status->setDescription('In Progress');
        $manager->persist($status);

        $status = new OutingStatus();
        $status->setDescription('Done');
        $manager->persist($status);

        $status = new OutingStatus();
        $status->setDescription('Cancelled');
        $manager->persist($status);

        $manager->flush();
        $allStatus = $manager->getRepository(OutingStatus::class)->findAll();

        //INSERTION CITIES
        $city = new City();
        $city->setName("NANTES");
        $city->setPostalCode("44000");
        $manager->persist($city);

        $city = new City();
        $city->setName("ST HERBLAIN");
        $city->setPostalCode("44800");
        $manager->persist($city);

        $city = new City();
        $city->setName("RENNES");
        $city->setPostalCode("35000");
        $manager->persist($city);

        $city = new City();
        $city->setName("REZE");
        $city->setPostalCode("44400");
        $manager->persist($city);

        $city = new City();
        $city->setName("LA ROCHE SUR YON");
        $city->setPostalCode("85000");
        $manager->persist($city);

        $city = new City();
        $city->setName("LA ROCHELLE");
        $city->setPostalCode("17000");
        $manager->persist($city);

        $city = new City();
        $city->setName("ARGENTEUIL");
        $city->setPostalCode("95100");
        $manager->persist($city);

        $city = new City();
        $city->setName("MONTCUQ");
        $city->setPostalCode("46201");
        $manager->persist($city);

        $manager->flush();
        $allCities = $manager->getRepository(City::class)->findAll();

        $place = new Place();
        $place->setName( $faker->name );
        $place->setAddress( $faker->streetAddress );
        $place->setLatitude($faker->latitude);
        $place->setLongitude( $faker->longitude );

        //plein de création de lieu


        for($i = 0; $i < 10; $i++) {

            $place = new Place();
            $place->setName( $faker->name );
            $place->setAddress( $faker->streetAddress );
            $place->setLatitude($faker->latitude);
            $place->setLongitude( $faker->longitude );

            //une ville au hasard
            $place->setCity($faker->randomElement($allCities) );

            //on sauvegarde dans la boucle
            $manager->persist($place);
        }
        //et on flush après la boucle (plus rapide)
        $manager->flush();
        $allPlaces = $manager->getRepository(Place::class)->findAll();

        //INSERTION USERS
        $user = new User();
        $user->setUsername("dv");
        $user->setPassword($this->encoder->encodePassword($user,"pwd123"));
        $user->setLastname("Vador");
        $user->setFirstname("Dark");
        $user->setPhone("0123456789");
        $user->setEmail("vador@tatooine.galaxy");
        $user->setIsAdmin(true);
        $user->setIsActive(true);
        $user->setCampus($faker->randomElement($allCampus));
        $manager->persist($user);

        $user = new User();
        $user->setUsername("hp");
        $user->setPassword($this->encoder->encodePassword($user,"pwd123"));
        $user->setLastname("Potter");
        $user->setFirstname("Harry");
        $user->setPhone("0123456789");
        $user->setEmail("hp@poudlard.magic");
        $user->setIsAdmin(false);
        $user->setIsActive(true);
        $user->setCampus($faker->randomElement($allCampus));
        $manager->persist($user);

        $user = new User();
        $user->setUsername("garfield");
        $user->setPassword($this->encoder->encodePassword($user,"pwd123"));
        $user->setLastname("Field");
        $user->setFirstname("Gar");
        $user->setPhone("0123456789");
        $user->setEmail("gf@miaou.cat");
        $user->setIsAdmin(true);
        $user->setIsActive(true);
        $user->setCampus($faker->randomElement($allCampus));
        $manager->persist($user);

        $manager->flush();

        $allUsers = $manager->getRepository(User::class)->findAll();

        for($i = 0; $i < 20; $i++) {

            $outing = new Outing();
            $outing->setName( $faker->name );
            $outing->setStartingTime( $faker->dateTimeBetween('now','+ 1 month') );
            $outing->setDuration($faker->randomFloat(2,1,12));
            $outing->setMaxDateInscription( $faker->dateTimeBetween("- 1 week",$outing->getStartingTime()) );
            $outing->setMaxNbInscriptions( $faker->randomDigitNotZero() );
            $outing->setOutingDetails( $faker->realTextBetween(25,200) );
            $outing->setCampus( $faker->randomElement($allCampus) );
            $outing->setPlace( $faker->randomElement($allPlaces) );
            $outing->setPlanner( $faker->randomElement($allUsers) );
            $outing->setStatus( $faker->randomElement($allStatus) );

            //on sauvegarde dans la boucle
            $manager->persist($outing);
        }
        //et on flush après la boucle (plus rapide)
        $manager->flush();

    }
}
