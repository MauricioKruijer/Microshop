<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 15/06/15
 * Time: 17:36
 */
$this->respond("/?", function($req, $res) {
    $res->redirect("/");
});
$this->respond("/products.json", function($req, $res) {
    $res->json([[
        'id' => 1,
        'name' => "Arduino Pro Mini 328",
        'short_description' => "This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. ",
        'price' => 995,
        'image' => "/webroot/images/product1/1.jpg"
    ], [
        'id' => 2,
        'name' => "Breadboard Full-Size (Bare)",
        'short_description' => "This is your tried and true full size solderless breadboard! It has 2 power buses, 10 columns, and 63 rows - with a total of 830 tie in points",
        'price' => 595,
        'image' => "/webroot/images/product2/1.jpg"
    ], [
        'id' => 3,
        'name' => "Break Away Headers Straight",
        'short_description' => "A row of headers - break to fit. 40 pins that can be cut to any size. Used with custom PCBs or general custom headers",
        'price' => 150,
        'image' => "/webroot/images/product3/1.jpg"
    ], [
        'id' => 4,
        'name' => "WiFi Module ESP8266",
        'short_description' => "The ESP8266 WiFi Module is a self contained SOC with integrated TCP/IP protocol stack that can give any microcontroller access to your WiFi network.",
        'price' => 695,
        'image' => "/webroot/images/product4/1.jpg"
    ], [
        'id' => 5,
        'name' => "Hook-Up Wire Assortment (Solid Core, 22 AWG)",
        'short_description' => "An assortment of colored wires: you know it’s a beautiful thing",
        'price' => 1695,
        'image' => "/webroot/images/product5/1.jpg"
    ], [
        'id' => 6,
        'name' => "Polymer Lithium Ion Battery 2000mAh",
        'short_description' => "These are very slim, extremely light weight batteries based on the new Polymer Lithium Ion chemistry. This is the highest energy density currently in production",
        'price' => 1295,
        'image' => "/webroot/images/product6/1.jpg"
    ]]);
});

$this->respond("/[i:id].json", function($req, $res) {
    $res->json([
        "id" => 1,
        "sku" => 'SEN-1337',
        "name" => "Product Name",
        "short_description" => "This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. ",
        "description" => "It's blue! It's thin! It's the Arduino Pro Mini! SparkFun's minimal design approach to Arduino. This is a 5V Arduino running the 16MHz bootloader. Arduino Pro Mini does not come with connectors populated so that you can solder in any connector or wire with any orientation you need. We recommend first time Arduino users start with the Uno R3. It's a great board that will get you up and running quickly. The Arduino Pro series is meant for users that understand the limitations of system voltage (5V), lack of connectors, and USB off board. \n We really wanted to minimize the cost of an Arduino. In order to accomplish this we used all SMD components, made it two layer, etc. This board connects directly to the FTDI Basic Breakout board and supports auto-reset. The Arduino Pro Mini also works with the FTDI cable but the FTDI cable does not bring out the DTR pin so the auto-reset feature will not work. There is a voltage regulator on board so it can accept voltage up to 12VDC. If you’re supplying unregulated power to the board, be sure to connect to the \"RAW\" pin and not VCC. \n The latest and greatest version of this board breaks out the ADC6 and ADC7 pins as well as adds footprints for optional I2C pull-up resistors!",
        "price" => 995,
        "image" => "/webroot/images/product1/1.jpg",
        "images" => [
            "/webroot/images/product1/1.jpg",
            "/webroot/images/product1/2.jpg",
            "/webroot/images/product1/3.jpg"
        ]
    ]);
});