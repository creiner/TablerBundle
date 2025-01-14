<?php

/*
 * This file is part of the Tabler bundle, created by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KevinPapst\TablerBundle\Event;

use KevinPapst\TablerBundle\Model\MenuItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Collect all MenuItemInterface objects that should be rendered in the menu section.
 */
class MenuEvent extends ThemeEvent
{
    /**
     * @var array
     */
    private $menuRootItems = [];
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @return MenuItemInterface[]
     */
    public function getItems(): array
    {
        return $this->menuRootItems;
    }

    /**
     * @param MenuItemInterface $item
     * @return MenuEvent
     */
    public function addItem($item)
    {
        $this->menuRootItems[$item->getIdentifier()] = $item;

        return $this;
    }

    /**
     * @param MenuItemInterface|string $item
     * @return MenuEvent
     */
    public function removeItem($item): MenuEvent
    {
        if ($item instanceof MenuItemInterface && isset($this->menuRootItems[$item->getIdentifier()])) {
            unset($this->menuRootItems[$item->getIdentifier()]);
        } elseif (\is_string($item) && isset($this->menuRootItems[$item])) {
            unset($this->menuRootItems[$item]);
        }

        return $this;
    }

    /**
     * @param string $id
     * @return MenuItemInterface|MenuItem|null
     */
    public function getRootItem($id)
    {
        return $this->menuRootItems[$id] ?? null;
    }

    /**
     * @return MenuItemInterface|MenuItem|null
     */
    public function getActive()
    {
        foreach ($this->getItems() as $item) {
            if ($item->isActive()) {
                return $item;
            }
        }

        return null;
    }
}
