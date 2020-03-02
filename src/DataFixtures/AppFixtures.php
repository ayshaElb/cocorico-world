<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Univers;
use App\Entity\Category;
use App\Entity\Producer;
use App\Entity\Subcategory;
use App\DataFixtures\Provider\DataProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\ImageProvider;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * l'encodeur de mot de passe 
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        $faker->addProvider(new ImageProvider($faker));


        // Création des Univers, des Categories et des sous categories
        $data = DataProvider::$data;

        $subcategories = [];
        foreach ($data as $key => $uni){
            $univers = new Univers();
            $univers->setName($key);
            $manager->persist($univers);
                foreach($uni as $cat => $subcat){
                    $category = new Category();
                    $category->setName($cat)
                            ->setUnivers($univers);
                    $manager->persist($category);
                        foreach($subcat as $value){
                        $subCategory = new Subcategory();
                        $subCategory->setName($value)
                                    ->setCategory($category);
                        $manager->persist($subCategory);
                        $subcategories[] = $subCategory;
                        }
                }
        }
        // on créer l'administrateur
        $user = new User();

        $hash = $this->encoder->encodePassword($user, "admin");

        $user   ->setEmail('admin@admin.com')
                ->setPassword($hash)
                ->setRoles(['ROLE_ADMIN'])
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setTelephone($faker->phoneNumber())
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                ->setCity($faker->city());
        $manager->persist($user); 
        
        //création des utilisateurs 
        for ($u=0; $u < 20 ; $u++) { 
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");

            $user   ->setEmail($faker->unique()->email())
                    ->setPassword($hash)
                    ->setRoles(['ROLE_USER'])
                    ->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setTelephone($faker->phoneNumber())
                    ->setAddress($faker->streetAddress())
                    ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                    ->setCity($faker->city());
            $manager->persist($user); 
        }

        // Création des producteurs 
        for ($u=0; $u < 20 ; $u++) { 
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");

            $user   ->setEmail($faker->unique()->email())
                    ->setPassword($hash)
                    ->setRoles(['ROLE_PRODUCER'])
                    ->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setTelephone($faker->phoneNumber())
                    ->setAddress($faker->streetAddress())
                    ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                    ->setCity($faker->city());
            $manager->persist($user);   
        
        
            for ($i = 0 ; $i < 1;  $i++) {
                $producer = new Producer();
                $producer   ->setSocialReason($faker->company(), $faker->companySuffix())
                            ->setEmail($faker->unique()->email())
                            ->setSiretNumber($faker->siret())
                            ->setAddress($faker->streetAddress())
                            ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                            ->setCity($faker->city())
                            ->setFirstname($faker->firstName())
                            ->setLastname($faker->lastName())
                            ->setTelephone($faker->phoneNumber())
                            ->setStatus($faker->randomElement(['EURL', 'SARL', 'MICRO ENTREPRENEUR']))
                            ->setAvatar($faker->ImageUrl())
                            ->setDescription($faker->sentence(3, true))
                            ->setUser($user);

                    
                $manager->persist($producer);

            // création des produits pour chaque producteur 
                for ($i=0; $i < mt_rand(3, 10) ; $i++) { 
                    $product = new Product();               

                    $product    ->setName($faker->sentence(2, true))
                                ->setPrice($faker->randomNumber(2))
                                ->setWeight($faker->randomNumber(3))
                                ->setQuantity($faker->numberBetween($min = 0, $max = 15))
                                ->setImage($faker->ImageUrl())
                                ->setProducer($producer)
                                ->setSubcategory($subcategories[$i]);
                    
                    $manager->persist($product);
                }
            }

        }        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
