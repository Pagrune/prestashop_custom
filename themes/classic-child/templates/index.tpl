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
              <section class="section-slide conteneur">
                  <div class="slider_container flex">
                      <div class="slide_1 fraise test">
                              <h2 class=" flex-2 slide_content">
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


                              <div class="three_container">

                              </div>
                              </div>
                      </div>
                  </div>
              </section>
              <section class="conteneur">
                  <div class="slideshow">
                      <div class="carousel">
                          <div class="slide active">
                              <p>Produit 1</p>
                          </div>
                          <div class="slide">
                              <p>Produit 2</p>
                          </div>
                          <div class="slide">
                              <p>Produit 3</p>
                          </div>
                          <div class="slide">
                              <p>Produit 4</p>
                          </div>

                      </div>

                      <div class="info-product">
                          <h2>Nos produits du moment</h2>
                          <div class="slide-product active" data-list="1" >
                              <h3>Crème Glacée Pistache Gourmande</h3>
                              <p>Découvrez la gourmandise de notre Crème Glacée Pistache Gourmande, élaborée avec des pistaches de qualité supérieure.Une explosion de saveurs riches et crémeuses pour les amateurs de pistache.</p>
                              <button class="btn">
                                  <a href="">Découvrir cette saveur</a>
                              </button>
                              <button class="btn fill">
                                  <a href="">Découvrir nos saveurs</a>
                              </button>
                          </div>
                          <div class="slide-product" data-list="2" >
                              <h3>Crème Glacée Cacao Noisette Délice</h3>
                              <p>Dégustez le délice de notre Crème Glacée Cacao Noisette Délice, une combinaison exquise de cacao riche et de noisettes croquantes. Une gourmandise irrésistible pour les amateurs de saveurs chocolatées.</p>
                              <button class="btn">
                                  <a href="">Découvrir cette saveur</a>
                              </button>
                              <button class="btn fill">
                                  <a href="">Découvrir nos saveurs</a>
                              </button>
                          </div>
                          <div class="slide-product" data-list="3" >
                              <h3>Cookies & Cream Ice Cream</h3>
                              <p>Dégustez le délicieux mélange de notre Crème Glacée Cookies & Cream Délicieux, une fusion crémeuse de glace à la vanille et de morceaux de cookies croquants. Une gourmandise irrésistible pour les amateurs de saveurs originales.</p>
                              <button class="btn">
                                  <a href="">Découvrir cette saveur</a>
                              </button>
                              <button class="btn fill">
                                  <a href="">Découvrir nos saveurs</a>
                              </button>
                          </div>
                          <div class="slide-product" data-list="4" >
                              <h3>Sorbet Cerise Noire Intense</h3>
                              <p>Découvrez l'intensité des saveurs avec notre Sorbet Cerise Noire Intense, élaboré avec des cerises noires juteuses. Une expérience gustative profonde pour les amateurs de fruits rouges.</p>
                              <button class="btn">
                                  <a href="">Découvrir cette saveur</a>
                              </button>
                              <button class="btn fill">
                                  <a href="">Découvrir nos saveurs</a>
                              </button>
                          </div>


                      </div>
                  </div>
              </section>
            {$HOOK_HOME nofilter}
          {/block}
        {/block}
      </section>
    {/block}
