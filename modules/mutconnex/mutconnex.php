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
            $this->registerHook('displayFooter');
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
        $secret = '3f3e572a8ae921360cb4c761e795a31f51c1a56ab6115a90b54b7643277183ae401913394c67f2d3c053791f4131b2c53504df10fa304c34a2cfb42ce0886d4d';

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
        $secret = '3f3e572a8ae921360cb4c761e795a31f51c1a56ab6115a90b54b7643277183ae401913394c67f2d3c053791f4131b2c53504df10fa304c34a2cfb42ce0886d4d';

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

    public function hookActionAdminLoginControllerLoginAfter()
    {
        //récupération de l'id du user connecté avec le context
        $id_customer = $this->context->customer->id;
        //génération d'une clé secrète pour mon jwt
        $secret = '4c565e7f30bcf8548ffe7a235f764a330570b77600215133ebd656e599d01c998f37e5f32a2b06d2bd4c7de32338144600a5178d1b0da547fdae64babae8df80';


        //JWT creation
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['id_customer' => $id_customer]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;


        setcookie('is_admin', $jwt);
    }

    public function hookActionAdminLoginControllerLogoutAfter()
    {
        setcookie('is_admin', '', time() - 3600);
    }

    public function hookActionCustomerLogoutAfter()
    {
        setcookie('mutconnex', '', time() - 3600);
    }



}
