<?php

namespace App\DataFixtures\Provider;


class DataProvider 
{
    public static $data = [

        'Fruit et Legume'=> [
            'Fruits' =>['Pomme', 'Poire', 'Orange'], 
            'Légumes' =>['Tomate', 'Courgette', 'Pomme de terre'],
            'Herbes aromatiques'=>['Basilic', 'Ciboulette', 'Coriandre'], 
            'Légumes secs'=>['Lentilles', 'Pois cassés', 'Haricot rouges'],
            ],
        'Boisson Alcoolisée '=> [
            'Vins'=>['Bordeaux rouge', 'Alsace blanc', 'Anjou rosé'],
            'Cidres'=>['Normand brut', 'Normand demi sec', 'Normand sec'],
            'Bières'=>['Blonde', 'Brune', 'Ambrée'],
            ],
        'Boisson sans Alcool'=> [
            'Jus de fruits'=>['Jus de raisin', 'Jus d\'orange', 'Jus de tomate'],
            'Boissons gazeuses'=>['Régional', 'National', 'Energisante'],
            'Boissons bio'=>['Jus de pomme', 'Jus de groseille', 'Jus de framboise']
            ],
        'Charcuterie'=> [
            'Foie gras'=>['Canard cru', 'Oie cru', 'Canard mi cuit','Oie mi cuit'],
            'Jambons'=>['Jambon cru fumé', 'Jambon blanc fumé', 'Lardons fumés'],
            'Pâtés'=>['Pâté de canard', 'Pâté de lapin', 'Pâté de caille'],
            ],
        'Epicerie Salée'=> [
            'Huiles'=>['d\'olives', 'De tournesol', 'De sésame'],
            'Sel et poivres'=>['Bretagne', 'Guerande', 'Camargue'],
            'Bocaux'=>['Plat régionaux', 'Plat nationaux'],
            ],
        'Epicerie Sucrée'=> [
            'Gateaux'=>['Entremets', 'Gateaux d\'anniversaire', 'Gateaux secs'],
            'Confitures'=>['Gelées', 'Confitures', 'Marmelades'],
            'Confiseries'=>['Chocolat', 'Sucre d\'orge', 'Confiserie en vrac']
            ],
        'Fromage'=> [
            'Fromage lait cru'=>['Comté', 'Emmental', 'Tomme de Savoie'],
            'Fromage pasteurisé'=>['Epoisse', 'Camembert', 'St Marcelin'],
            'Cremerie'=>['Crème legère', 'Crème semi épaisse', 'crème épaisse'],
            ],        
        'Produit de la Mer'=> [
            'Poissons'=>['Bar', 'Sole', 'Raie'],
            'Crustacés'=>['Crabe', 'Homard', 'Araignées'],
            'Coquillages'=>['Huîtres', 'Moules', 'Bigorneaux'],
            ],
        'Produit Elaboré'=> [
            'Plats préparés'=>['Régionaux', 'Nationaux'],
            'Friands'=>['Feuilleté au fromage', 'Feuilleté à la viande', 'Feuilleté vegan'],
            'Pizzas'=>['Base tomate', 'Base crème fraiche'],
            ],
        'Viande'=> [
            'Boeuf'=>['Filet', 'Entrecôte', 'Bavette'],
            'Veau'=>['Escalope', 'Jarret', '1/2 veau'],
            'Agneau'=>['Gigot', 'Côtellette', '1/2 agneau'],
            ],
        
        ];

   
}