<?php
/**
 * 2007-2023 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2023 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class MutConnex extends Module
{

    public function __construct()
    {
        $this->name = 'mutconnex';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'MyLittleThings';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Connexion Mutualisée');
        $this->description = $this->l('mise dans un cookie de données d\'authentification d\'un user');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);


    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('MUTCONNEX_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('actionAuthentication') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('actionAdminLoginControllerLoginAfter') &&
            $this->registerHook('actionAdminLoginControllerLogoutAfter') &&
            $this->registerHook('actionCustomerLogoutAfter') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('MUTCONNEX_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMutConnexModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'MUTCONNEX_LIVE_MODE' => Configuration::get('MUTCONNEX_LIVE_MODE', true),
            'MUTCONNEX_ACCOUNT_EMAIL' => Configuration::get('MUTCONNEX_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'MUTCONNEX_ACCOUNT_PASSWORD' => Configuration::get('MUTCONNEX_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMutConnexModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'MUTCONNEX_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'MUTCONNEX_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'MUTCONNEX_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . 'views/dist/assets/main-JYlaWuTQ.js');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');

    }


    public function hookActionAuthentication()
    {
        $id_customer = $this->context->customer->id;
        $name_customer = $this->context->customer->firstname;
        $secret = 'pouet';

// Encodage JSON
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['id_customer' => $id_customer, 'name_customer' => $name_customer]);

// Encodage Base64 URL-safe
        $base64UrlHeader = $this->base64url_encode($header);
        $base64UrlPayload = $this->base64url_encode($payload);

// Signature HMAC SHA-256
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

// Encodage Base64 URL-safe de la signature
        $base64UrlSignature = $this->base64url_encode($signature);

// Création du JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

// Stockage du JWT (exemple avec cookie)
        setcookie('mutconnex', $jwt);


    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function hookActionCustomerAccountAdd()
    {
        //récupération de l'id du user connecté avec le context
        $id_customer = $this->context->customer->id;
        $name_customer = $this->context->customer->firstname;
        $secret = 'pouet';

// Encodage JSON
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['id_customer' => $id_customer, "isAdmin"=>false, 'name_customer' => $name_customer]);

// Encodage Base64 URL-safe
        $base64UrlHeader = $this->base64url_encode($header);
        $base64UrlPayload = $this->base64url_encode($payload);

// Signature HMAC SHA-256
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

// Encodage Base64 URL-safe de la signature
        $base64UrlSignature = $this->base64url_encode($signature);

// Création du JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

// Stockage du JWT (exemple avec cookie)
        setcookie('mutconnex', $jwt);
    }

    public function hookActionAdminLoginControllerLoginAfter()
    {
        //récupération de l'id du user connecté avec le context
        $id_customer = $this->context->customer->id;
        //génération d'une clé secrète pour mon jwt
        $secret = 'pouet';


        //JWT creation
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['id_customer' => $id_customer, "isAdmin"=>true]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;


        setcookie('mutconnex', $jwt);
    }

    public function hookActionAdminLoginControllerLogoutAfter()
    {
        setcookie('mutconnex', '', time() - 3600);
    }

    public function hookActionCustomerLogoutAfter()
    {
        setcookie('mutconnex', '', time() - 3600);
    }

    public function hookDisplayProductAdditionalInfo() {
        $product = $this->context->controller->getProduct();
        if ($product) {
            // Récupérer l'ID du produit
            $productId = $product->id;

            // Effectuer une requête cURL vers votre API FastAPI
            $apiUrl = "https://api.pauline.anthony-kalbe.fr/get_recommendations";
            $postData = array('input_product_id' => $productId);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "Erreur cURL : " . $err;
            } else {
                // Traiter la réponse de l'API FastAPI
                $responseData = json_decode($response, true);

                echo '<h2>Une recommandation rien que pour vous</h2><div class="flex" style="justify-content: space-around">';
                // Récupérer les informations des produits à partir des IDs et les afficher
                $counter = 0; // Initialiser le compteur

                foreach ($responseData as $recommendation) {
                    $recommendedProductId = $recommendation['id'];

                    // Récupérer les informations du produit depuis PrestaShop
                    $recommendedProduct = $this->getProductInfoFromPrestaShop($recommendedProductId);

                    // Afficher les informations du produit seulement pour les 3 premiers
                    if ($counter < 3) {
                        ob_start();
                        ?>
                        <div class="recommendedProduct flex" style=" background : radial-gradient( farthest-corner at 190px 230px,#F6CC8C, #EA515F ); width : 300px; flex-direction : column; align-items: center; border-radius: 20px; min-height: 64vh; padding-bottom: 12px; justify-content: space-between">
                            <img src="<?= $recommendedProduct['image'] ?>" style="width: 100%; border-radius: 20px 20px 0px 0px"></img>
                            <h3 style="text-align: center; color: #FFFFFF; width: 90%"><?= $recommendedProduct['title'] ?></h3>
                            <p style="color: #FFFFFF;"><?= $recommendedProduct['price'] ?> €</p>
                            <button class="btn fill">
                                <a href="<?= $recommendedProduct['link_product'] ?>">Voir le produit</a>
                            </button>
                        </div>
                        <?php
                        $showproduct = ob_get_clean();
                        echo $showproduct;

                        // Incrémenter le compteur
                        $counter++;
                    }
                }

                echo '</div>';
            }
        }

    }

// Fonction pour récupérer les informations du produit depuis PrestaShop
    private function getProductInfoFromPrestaShop($productId) {
        // Utilisez la fonction getProduct() pour obtenir les informations du produit dans PrestaShop
        $recommendedProduct = new Product($productId, false, $this->context->language->id);

        // Vous pouvez maintenant accéder aux propriétés du produit, par exemple, le nom, le prix, l'image, etc.
        $recommendedProductName = $recommendedProduct->name;
        $recommendedProductPrice = $recommendedProduct->getPrice(true);
        $recommendedProductLink = $recommendedProduct->getLink();

        // Récupérer l'URL de l'image en utilisant la première image associée au produit
        //$coverId = $recommendedProduct->getCover($productId)['id'];
        $recommendedProductImage = $recommendedProduct->link_rewrite;
        //$recommendedProductImage = $recommendedProduct->getImages(1);
        $images = Image::getImages(1, $productId);
        $image = new Image($images[0]['id_image']);
        $link_img =  _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
//        var_dump($recommendedProduct);
//        $recommendedProductImage = $recommendedProduct->getCover($productId)->bySize('large_default')['url'];

        // Retournez les informations du produit sous forme de tableau associatif
        return array(
            'link_product' => $recommendedProductLink,
            'id' => $productId,
            'title' => $recommendedProductName,
            'price' => $recommendedProductPrice,
            'image' => $link_img,
            // Ajoutez d'autres champs nécessaires
        );
    }

    public function hookDisplayHome()
    {
        $product_info_content = ""; // Initialize outside the loop
        $usedProductIds = array(); // To keep track of used product IDs
        $carousel_content = ""; // Initialize outside the loop

        for ($i = 1; $i <= 4; $i++) {
            // Initialize random number
            $randomNumber = 0;

            // Loop until a unique random number is found
            while (true) {
                $randomNumber = rand(198, 220);

                if (!in_array($randomNumber, $usedProductIds)) {
                    break;
                }
            }

            $usedProductIds[] = $randomNumber; // Add the used product ID to the array

            if (Product::existsInDatabase($randomNumber)) {
                // Create an instance of the Product class with the existing product ID
                $product = new Product($randomNumber, false, $this->context->language->id);

                // Get images associated with the product
                $images = Image::getImages($this->context->language->id, $product->id);

                if (!empty($images)) {
                    $image = new Image($images[0]['id_image']);
                    $link_img = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $image->getExistingImgPath() . ".jpg";

                    // Build product information content
                    $product_info_content .= "<div class='slide-product";
                    $product_info_content .= $i == 1 ? " active" : ""; // Add 'active' class for the first item
                    $product_info_content .= "' data-list='$i'>";
                    $product_info_content .= "<h3>{$product->name}</h3>";  // Use actual product name
                    $product_info_content .= "<p>{$product->description_short}</p>";  // Use actual product description
                    $product_info_content .= "<button class='btn'><a href=''>Découvrir cette saveur</a></button>";
                    $product_info_content .= "<button class='btn fill'><a href=''>Découvrir nos saveurs</a></button>";
                    $product_info_content .= "</div>";

                    if ($i == 1) {
                        $carousel_content .= "<div class='slide active'><img src='$link_img' style='border-radius: 100%;'></img></div>";
                    } else {
                        $carousel_content .= "<div class='slide'><img src='$link_img'></img></div>";
                    }
                } else {
                    $carousel_content = "No images available for the product with ID $randomNumber.";
                }
            } else {
                $carousel_content = "Product with ID $randomNumber does not exist.";
            }
        }

        // Assign the product info content to a Smarty variable
        $smarty = $this->context->smarty;
        $smarty->assign('product_info_content', $product_info_content);

        // Assign the carousel content to a Smarty variable
        $smarty->assign('carousel_content', $carousel_content);
    }




}
