{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{extends file='page.tpl'}

    {block name='page_content_container'}

      <section id="content" class="page-home">
        {block name='page_content_top'}{/block}

        {block name='page_content'}
          {block name='hook_home'}
              <section class="section-slide conteneur" style="
    display: flex; align-items: center;">
                  <div class="slider_container flex">
                      <div class="slide_1  test">

                                  <h1>
                                      Laissez-vous emporter dans un univers complètement givré avec My Little Things
                                      <div class="seperator">
                                          <img src="https://anthony-kalbe.fr/themes/classic-child/assets/img/line.png" alt="">
                                      </div>
                                  </h1>
                                  <div class="p">
                                      Plongez dans un monde de délices rafraîchissants avec notre irrésistible glace aux fraises.
                                      Chaque bouchée est une explosion de saveurs fruitées, une symphonie de fraîcheur qui égaye les papilles à chaque instant.
                                  </div>
                      </div>
                      <div id="scene-container">
                          <canvas class="webgl"></canvas>
                      </div>
                      <div id="scene-container-deux"></div>
                  </div>

              </section>
              <section class="conteneur none">
                  <div class="slideshow">
                      <div class="carousel">
                          {$carousel_content nofilter}
                      </div>
                      <div class="info-product">
                          <h2>Nos produits du moment</h2>
                          {$product_info_content nofilter}
                      </div>
                  </div>
              </section>


              <section class="Part2">
                  <div class="items">
                      <div class="item active">
                          <img  width="100%" src="" alt="">
                      </div>
                      <div class=" item next">
                          <img width="100%"  src="" alt="">
                      </div>
                      <div class="item">
                          <img width="100%"  src="" alt="">
                      </div>
                      <div class="item">
                          <img  width="120%"  src="" alt="">
                      </div>
                      <div class="item prev">
                          <img  width="120%"  src="" alt="">
                      </div>
                      <div class="button-container">
                          <div class="button"><i class="fas fa-angle-left"></i></div>
                          <div class="button"><i class="fas fa-angle-right"></i></div>
                      </div>
                  </div>

                  <div class="margin">
                      <h3 class="xTrois">Nos glaces les plus populaires</h3>
                      <h2 class="xDeux">Vanille  X Noix de Macadamia & baies sauvage</h2>
                      <div class="description">
                          <p>Plongez dans un <span class="red">univers de douceur </span> et de <span class="red">croquant</span> avec notre <span class="red">irrésistible</span> glace saveur vanille aux noix de macadamia.</p>
                          <p>Chaque cuillère est une <span class="red">symphonie de saveurs</span>, une danse délicate entre la vanille <span class="red">veloutée</span> et le <span class="red">croquant</span> des noix de macadamia.</p>
                      </div>
                      <div class="ButtonsFlexColler">
                          <button class="btn">
                              <a href="">Découvrir cette saveur</a>
                          </button>
                          <button class="btn fill">
                              <a href="">Découvrir nos saveurs</a>
                          </button>
                      </div>
                  </div>
                  <!--<img  class="posi" src="Img/chips_chocolate 1.png" alt="">-->
              </section>

              {$HOOK_HOME nofilter}
          {/block}
        {/block}


    {literal}
        <script src="themes/classic-child/assets/js/dist/assets/index-4a13b25a.js"></script>;
        {/literal}
    {/block}
