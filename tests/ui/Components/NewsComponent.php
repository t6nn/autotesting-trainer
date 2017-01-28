<?php
namespace Newsy\Tests\Ui\Components;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use \Lmc\Steward\Component\AbstractComponent;
use Lmc\Steward\Test\AbstractTestCaseBase;

/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 28.01.2017
 * Time: 22:37
 */
class NewsComponent extends AbstractComponent
{

    const HEADER_SELECTOR = 'h2';
    const FULL_STORY_LINK_SELECTOR = 'a.btn.btn-default';
    const RATE_OPTIONS_SELECTOR = 'fieldset.rating input[type="radio"]';

    /**
     * Root element of a news story.
     *
     * @var RemoteWebElement
     */
    private $root;

    public function __construct(AbstractTestCaseBase $tc, RemoteWebElement $root)
    {
        parent::__construct($tc);
        $this->root = $root;
    }

    public function findHeader() {
        return $this->root->findElement(WebDriverBy::cssSelector(self::HEADER_SELECTOR));
    }

    public function findFullStoryButton() {
        return $this->root->findElement(WebDriverBy::cssSelector(self::FULL_STORY_LINK_SELECTOR));
    }

    public function findRateOptions() {
        return $this->root->findElements(WebDriverBy::cssSelector(self::RATE_OPTIONS_SELECTOR));
    }

}