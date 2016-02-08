<?php
/**
 * @package Default (Template) Project.
 * @author: A.A.Treitjak <a.treitjak@gmail.com>
 * @copyright: 2012 - 2015 BelVG.com
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$birds = array(
    array(
        'bird_name' => 'Blackbird',
        'latin_name' => 'Turdus Merula',
        'family' => 'Chats and Thrushes',
        'overview' => 'One of the easiest garden birds to identify. The male Blackbird is black with the females being brown. The male of the species is jet black with a bright yellow beak, though the beak colour is more brown on younger birds. The female bird is similar in size but its plumage is brown in colour. They are very territorial in nature and if other blackbirds encroach on their territory they are very fast in trying to remove them from their patch. They are one of the first birds in the morning to start the dawn chorus and usually the last bird singing in the evening.',
        'distribution_map' => 'Throughout the UK.',
        'habitat' => 'Found in most gardens in the UK. Also found in most hedgerows and countryside.',
        'population' => '5,100,000 breeding pairs and 10-15million wintering bird.',
        'breeding' => 'They make their nest from vegetation combined with some mud for reinforcement. They will use an open-fronted nest box if offered. The eggs are brown freckled greenish-blue in colour and the blackbird lays between 3 and 5 eggs in each clutch. In a season they may have from 2 to 4 clutches.',
        'food_diet' => 'Blackbirds will eat berries as soon as they ripen including garden fruits, raspberries and strawberries. They also like earthworms and insects. Blackbirds enjoy our VHF Ground Mix, Robin and Friends Mix, Chopped Peanuts, Sultanas, Oats, all suet products andlive mealworms.',
        'trends' => 'Almost 5 million breeding pairs in the UK. There are approximately 10 to 15 million Blackbirds wintering here too.',
        'behaviour' => 'Blackbirds rarely socially interact, preferring to be solitary. They will establish their territory in their first year and remain there throughout their lives',
        'audio_file' => '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/126561222&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>',
        'video_file' => '<iframe id="ytplayer" type="text/html" width="100%" height="435"  src="http://www.youtube.com/embed//JnquHt-s1-s"  frameborder="0"/></iframe>',
    ),
    array(
        'bird_name' => 'Robin',
        'latin_name' => 'Erithacus Rubecula',
        'family' => 'Chats and Thrushes',
        'overview' => 'These brightly coloured small birds commonly found in many gardens and are one of Britain\'s favourite birds. The robin will take the juicy insects and worms as quickly as they appear. They are territorial and very aggressive in nature and have a ferocious appetite. Their brightly coloured red breast is their most attractive feature and for this reason the robin is seen on Christmas cards. One of the most distinctive birds seen in Britain and certainly the best loved. Both male and female birds are identical with their red breast and olive brown upper parts. The juveniles lack the red breast and has a speckled breast.',
        'distribution_map' => 'Commonly seen throughout the UK.',
        'habitat' => 'Breeds in woods and copses with plenty of undergrowth. They are also commonly seen in hedges, gardens, parks. Not that commonly seen in the open countryside.',
        'population' => '6,700,000 breeding pairs',
        'breeding' => 'They normally have two broods per year. The robin will virtually nest anywhere and in anything. The nest is made up of grass, leaves, and moss and lined with hair. They will often use an open fronted nest box in gardens',
        'food_diet' => 'Insects, worms, snails, seeds and fruit form the back bone to the Robins diet. They are commonly seen in gardens at bird tables and can be attracted by a number of products. Robins especially enjoy live mealworms, and you can have great fun in watching them feed. Other products that they enjoy are sunflower hearts, sunflower heart chips, Won\'t Grow Mix, Ultimate Energy with Mealworms, Ultimate energy with Suet, fat balls and suet pellets.',
        'trends' => 'There are over 5.9 million pairs in Britain with the current population stable.',
        'behaviour' => 'The robin will maintain the same territory all year round, in summer by a mated pair and in winter by an individual. The red breasts of the robin are part of their terriotory defence.',
    ),
    array(
        'bird_name' => 'Blackbird2',
        'latin_name' => 'Turdus Merula',
        'family' => 'Chats and Thrushes',
        'overview' => 'One of the easiest garden birds to identify. The male Blackbird is black with the females being brown. The male of the species is jet black with a bright yellow beak, though the beak colour is more brown on younger birds. The female bird is similar in size but its plumage is brown in colour. They are very territorial in nature and if other blackbirds encroach on their territory they are very fast in trying to remove them from their patch. They are one of the first birds in the morning to start the dawn chorus and usually the last bird singing in the evening.',
        'distribution_map' => 'Throughout the UK.',
        'habitat' => 'Found in most gardens in the UK. Also found in most hedgerows and countryside.',
        'population' => '5,100,000 breeding pairs and 10-15million wintering bird.',
        'breeding' => 'They make their nest from vegetation combined with some mud for reinforcement. They will use an open-fronted nest box if offered. The eggs are brown freckled greenish-blue in colour and the blackbird lays between 3 and 5 eggs in each clutch. In a season they may have from 2 to 4 clutches.',
        'food_diet' => 'Blackbirds will eat berries as soon as they ripen including garden fruits, raspberries and strawberries. They also like earthworms and insects. Blackbirds enjoy our VHF Ground Mix, Robin and Friends Mix, Chopped Peanuts, Sultanas, Oats, all suet products andlive mealworms.',
        'trends' => 'Almost 5 million breeding pairs in the UK. There are approximately 10 to 15 million Blackbirds wintering here too.',
        'behaviour' => 'Blackbirds rarely socially interact, preferring to be solitary. They will establish their territory in their first year and remain there throughout their lives',
    ),
    array(
        'bird_name' => 'Robin2',
        'latin_name' => 'Erithacus Rubecula',
        'family' => 'Chats and Thrushes',
        'overview' => 'These brightly coloured small birds commonly found in many gardens and are one of Britain\'s favourite birds. The robin will take the juicy insects and worms as quickly as they appear. They are territorial and very aggressive in nature and have a ferocious appetite. Their brightly coloured red breast is their most attractive feature and for this reason the robin is seen on Christmas cards. One of the most distinctive birds seen in Britain and certainly the best loved. Both male and female birds are identical with their red breast and olive brown upper parts. The juveniles lack the red breast and has a speckled breast.',
        'distribution_map' => 'Commonly seen throughout the UK.',
        'habitat' => 'Breeds in woods and copses with plenty of undergrowth. They are also commonly seen in hedges, gardens, parks. Not that commonly seen in the open countryside.',
        'population' => '6,700,000 breeding pairs',
        'breeding' => 'They normally have two broods per year. The robin will virtually nest anywhere and in anything. The nest is made up of grass, leaves, and moss and lined with hair. They will often use an open fronted nest box in gardens',
        'food_diet' => 'Insects, worms, snails, seeds and fruit form the back bone to the Robins diet. They are commonly seen in gardens at bird tables and can be attracted by a number of products. Robins especially enjoy live mealworms, and you can have great fun in watching them feed. Other products that they enjoy are sunflower hearts, sunflower heart chips, Won\'t Grow Mix, Ultimate Energy with Mealworms, Ultimate energy with Suet, fat balls and suet pellets.',
        'trends' => 'There are over 5.9 million pairs in Britain with the current population stable.',
        'behaviour' => 'The robin will maintain the same territory all year round, in summer by a mated pair and in winter by an individual. The red breasts of the robin are part of their terriotory defence.',
    ),
);

foreach ($birds as $bird) {
    Mage::getModel('birdlibrary/bird')
        ->setData($bird)
        ->save();
}


$installer->endSetup();