<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_theme\Kernel;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BreadcrumbTest.
 */
class BreadcrumbTest extends AbstractKernelTestBase {

  /**
   * Test a basic breadcrumb is themed using ECL breadcrumb component.
   *
   * @throws \Exception
   */
  public function testBreadcrumbRendering(): void {
    $links = [
      'Home' => '<front>',
      'Test' => '<front>',
    ];

    $breadcrumb = new Breadcrumb();
    foreach ($links as $title => $url) {
      $breadcrumb->addLink(Link::createFromRoute($title, $url));
    }
    $render_array = $breadcrumb->toRenderable();
    $html = $this->renderRoot($render_array);
    $crawler = new Crawler($html);

    // Assert wrapper contains ECL class.
    $actual = $crawler->filter('nav.ecl-breadcrumb');
    $this->assertCount(1, $actual);

    // Assert links are rendered correctly.
    $position = 0;
    foreach ($links as $title => $url) {
      $link = $crawler->filter('ol.ecl-breadcrumb__segments-wrapper li.ecl-breadcrumb__segment a.ecl-breadcrumb__link')->eq($position);
      $this->assertEquals($title, trim($link->text()));
      $position++;
    }
  }

}
