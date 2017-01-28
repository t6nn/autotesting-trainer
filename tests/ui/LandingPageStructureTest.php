<?php
namespace Newsy\Tests\Ui;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Lmc\Steward\Test\AbstractTestCase;
use Newsy\Tests\Ui\Components\NewsComponent;

class LandingPageStructureTest extends AbstractTestCase
{

    const PAGE_URL = 'http://localhost:1234';

    /**
     * @before
     */
    public function init()
    {
        $this->wd->get(self::PAGE_URL);
    }

    public function testShouldHaveNameInTitle() {
        $this->assertContains('Newsy', $this->wd->getTitle(), 'Application name must be in the title.');
    }

    public function testShouldDisplayThreeNewsItems() {
        $news = $this->findMultipleByCss('div.news-item');
        $this->assertCount(3, $news, 'There must be three news items on the front page');
        $index = 1;
        foreach ($news as $item) {
            $this->validateNewsItem($item, $index++);
        }
    }

    private function validateNewsItem(RemoteWebElement $item, $index) {
        $newsComponent = new NewsComponent($this, $item);
        $this->assertNotEmpty($newsComponent->findHeader()->getText(), "Item #$index header must not be empty");
        $this->assertCount(5, $newsComponent->findRateOptions(), "Item #$index must have a 5-star rating option.");
        $this->assertContains('View full story', $newsComponent->findFullStoryButton()->getText(), "Item #$index full story button must have a correct title");
    }

}