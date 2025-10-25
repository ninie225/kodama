<?php

namespace App\DataFixtures;

use App\Entity\Plat;
use App\Entity\Detail;
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PlatsFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Plats

        $plat1 = new Plat();
        $plat1->setNom('Buns aux haricots rouges');
        $plat1->setPrix(7.50);
        $plat1->setFilm('Le voyage de Chihiro');
        $plat1->setDetail('Plongez dans l’univers onirique du Voyage de Chihiro avec ces buns moelleux et dorés, fourrés d’une douce pâte de haricots rouges maison. Une douceur réconfortante, comme celles que Chihiro aurait pu déguster dans le monde des esprits.');
        $plat1->setPhoto('images/uploads/carte/buns.jpg');
        $plat1->setCategorie('entree');
        $plat1->setActive(true);
        $manager->persist($plat1);

        $plat2 = new Plat();
        $plat2->setNom('Ramen');
        $plat2->setPrix(12.00);
        $plat2->setFilm('Ponyo sur la falaise');
        $plat2->setDetail('Un bol réconfortant comme ceux que Sōsuke partage avec Ponyo, où se mêlent des nouilles fermes baignant dans un bouillon doré, inspiré des saveurs de la mer et de la terre. Garnis de lamelles de porc tendre, d’algues croquantes, et d’un œuf mariné, ces ramen évoquent la douceur des vagues et la chaleur d’un repas partagé en bord de mer.');
        $plat2->setPhoto('images/uploads/carte/ramen.jpg');
        $plat2->setCategorie('plat');
        $plat2->setActive(true);
        $manager->persist($plat2);

        $plat3 = new Plat();
        $plat3->setNom('Umeboshi');
        $plat3->setPrix(10.50);
        $plat3->setFilm('Mon voisin Totoro');
        $plat3->setDetail('Ces prunes salées et acidulées, marinées avec soin, rappellent les trésors cachés dans la forêt de Totoro. Leur goût vif et ensoleillé évoque les pique-niques improvisés sous les grands arbres, entre rires et rencontres magiques. Parfaites pour accompagner un bento ou un riz blanc, elles apportent une touche de joie et d’aventure à chaque bouchée..');
        $plat3->setPhoto('images/uploads/carte/umeboshi.jpg');
        $plat3->setCategorie('plat');
        $plat3->setActive(true);
        $manager->persist($plat3);

        $plat4 = new Plat();
        $plat4->setNom('Oeufs brouillés avec bacon');
        $plat4->setPrix(8.00);
        $plat4->setFilm('Le château ambulant');
        $plat4->setDetail('Des œufs crémeux et onctueux, mélangés à des morceaux de bacon croustillant, comme ceux que Sophie aurait pu préparer dans la cuisine en mouvement du Château Ambulant. Ce plat réconfortant, simple mais généreux, rappelle les matins où tout est possible, entre sorts malicieux et voyages imprévus.');
        $plat4->setPhoto('images/uploads/carte/oeufs.jpg');
        $plat4->setCategorie('entree');
        $plat4->setActive(true);
        $manager->persist($plat4);

        $plat5 = new Plat();
        $plat5->setNom('Choco-Totoro');
        $plat5->setPrix(6.00);
        $plat5->setFilm('Mon voisin Totoro');
        $plat5->setDetail('Inspirée par la magie de Mon Voisin Totoro, cette boisson onirique vous transporte dans la forêt enchantée de Hayao Miyazaki. Un mélange velouté de chocolat chaud, surmonté d’une généreuse touche de crème fouettée, évoque la douceur de l’abri-bus sous la pluie. La poudre de matcha saupoudrée rappelle les sous-bois mystérieux, tandis qu’une noix enrobée de chocolat et une feuille de capucine rouge orangé rendent hommage aux glands adorés de Totoro et à son célèbre parapluie.');
        $plat5->setPhoto('images/uploads/carte/chocototoro.jpg');
        $plat5->setCategorie('boisson');
        $plat5->setActive(true);
        $manager->persist($plat5);

        $plat6 = new Plat();
        $plat6->setNom("L'épouventail enchanté");
        $plat6->setPrix(5.50);
        $plat6->setFilm('Le château ambulant');
        $plat6->setDetail('Inspirée par l’épouvantail ensorcelé du Château Ambulant, cette limonade rafraîchissante marie la douceur acidulée de la pomme à une touche aromatique de romarin. Servie dans un verre élégant, elle est ornée d’un chapeau miniature rappelant celui du mystérieux personnage, et d’un stick bretzel pour une note ludique et gourmande. Une boisson envoûtante, aussi magique que le film de Miyazaki, à déguster pour s’évader un instant.');
        $plat6->setPhoto('images/uploads/carte/epouventail.jpeg');
        $plat6->setCategorie('boisson');
        $plat6->setActive(true);
        $manager->persist($plat6);

        $plat7 = new Plat();
        $plat7->setNom('Le Calcifer');
        $plat7->setPrix(6.50);
        $plat7->setFilm('Le château ambulant');
        $plat7->setDetail('Inspirée par le feu espiègle de Calcifer, cette boisson chaude réconfortante marie la richesse du chocolat chaud à une pointe de cannelle et une touche de piment doux, évoquant la chaleur et la vivacité du personnage. Surmontée d’une généreuse couche de crème fouettée et saupoudrée de cacao, elle est décorée d’une étoile en chocolat rappelant les flammes dansantes de Calcifer.');
        $plat7->setPhoto('images/uploads/carte/calcifer.jpg');
        $plat7->setCategorie('boisson');
        $plat7->setActive(true);
        $manager->persist($plat7);

        $plat8 = new Plat();
        $plat8->setNom('Gâteau au chocolat');
        $plat8->setPrix(7.00);
        $plat8->setFilm('Kiki la petite sorcière');
        $plat8->setDetail('Un gâteau au chocolat moelleux et riche, inspiré par les douceurs que Kiki pourrait préparer dans sa boulangerie. Chaque bouchée fondante évoque la magie de l’enfance et l’aventure, avec une texture légère et un goût intense de cacao, parfait pour accompagner une tasse de thé ou de café.');
        $plat8->setPhoto('images/uploads/carte/gateau.jpg');
        $plat8->setCategorie('dessert');
        $plat8->setActive(true);
        $manager->persist($plat8);

        $plat9 = new Plat();
        $plat9->setNom('Cheesecake Totoro');
        $plat9->setPrix(7.50);
        $plat9->setFilm('Mon voisin Totoro');
        $plat9->setDetail('Un cheesecake onctueux et léger, décoré avec soin pour ressembler à Totoro, le personnage emblématique du film. La base croustillante en biscuits contraste avec la douceur de la garniture au fromage, tandis que les détails en chocolat et fruits frais apportent une touche ludique et gourmande.');
        $plat9->setPhoto('images/uploads/carte/cheesecake.jpg');
        $plat9->setCategorie('dessert');
        $plat9->setActive(true);
        $manager->persist($plat9);

        $plat10 = new Plat();
        $plat10->setNom('Ragoût');
        $plat10->setPrix(11.00);
        $plat10->setFilm('Arrietty, le petit monde des chapardeurs');
        $plat10->setDetail('Un ragoût réconfortant, comme ceux que préparait la mère d’Arrietty dans sa cuisine miniature. Des morceaux tendres de viande mijotés avec des légumes de saison, le tout parfumé d’herbes fraîches. Un plat qui rappelle la chaleur d’un foyer caché, à déguster comme un secret bien gardé.');
        $plat10->setPhoto('images/uploads/carte/ragout.webp');
        $plat10->setCategorie('plat');
        $plat10->setActive(true);
        $manager->persist($plat10);


        // Utilisateurs

        $user1 = new Utilisateur();
        $user1->setEmail('admin@test.com');
        $user1->setPassword($this->hasher->hashPassword($user1, '1234'));
        $user1->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);

        $user2 = new Utilisateur();
        $user2->setEmail('chef@test.com');
        $user2->setPassword($this->hasher->hashPassword($user2, '1234'));
        $user2->setRoles(['ROLE_CHEF']);
        $manager->persist($user2);

        $user3 = new Utilisateur();
        $user3->setEmail('client1@test.com');
        $user3->setPassword($this->hasher->hashPassword($user3, '1234'));
        $user3->setRoles(['ROLE_CLIENT']);
        $user3->setNom('Dupont');
        $user3->setPrenom('Jean');
        $manager->persist($user3);

        $user4 = new Utilisateur();
        $user4->setEmail('client2@test.com');
        $user4->setPassword($this->hasher->hashPassword($user4, '1234'));
        $user4->setRoles(['ROLE_CLIENT']);
        $user4->setNom('Petit');
        $user4->setPrenom('Lea');
        $manager->persist($user4);

        // Commandes

        $commande1 = new Commande();
        $commande1->setDateCommande(new \DateTime('2025-10-24 12:05:55'));
        $commande1->setEtat(2);
        $commande1->setUtilisateur($user3);
        $manager->persist($commande1);

        $commande2 = new Commande();
        $commande2->setDateCommande(new \DateTime('2025-10-24 19:30:55'));
        $commande2->setEtat(3);
        $commande2->setUtilisateur($user4);
        $manager->persist($commande2);


        // Détails de chaque commande

        // Commande 1 (Jean Dupont)
        $detail1 = new Detail();
        $detail1->setPlat($plat1);
        $detail1->setQuantite(2);
        $commande1->addDetail($detail1);
        $manager->persist($detail1);

        $detail2 = new Detail();
        $detail2->setPlat($plat4);
        $detail2->setQuantite(2);
        $commande1->addDetail($detail2);
        $manager->persist($detail2);

        // Commande 2 (Lea Petit)
        $detail3 = new Detail();
        $detail3->setPlat($plat2);
        $detail3->setQuantite(2);
        $commande2->addDetail($detail3);
        $manager->persist($detail3);

        $detail4 = new Detail();
        $detail4->setPlat($plat9);
        $detail4->setQuantite(2);
        $commande2->addDetail($detail4);
        $manager->persist($detail4);

        $detail5 = new Detail();
        $detail5->setPlat($plat7);
        $detail5->setQuantite(1);
        $commande2->addDetail($detail5);
        $manager->persist($detail5);

        // Calcul du total de chaque commande

        foreach ([$commande1, $commande2] as $commande) {
            $total = 0;
            foreach ($commande->getDetails() as $detail) {
                $plat = $detail->getPlat();
                $total += $plat->getPrix() * $detail->getQuantite();
            }
            $commande->setTotal($total);
        }
        $manager->flush();
    }
}
