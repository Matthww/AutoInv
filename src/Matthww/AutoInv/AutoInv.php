<?php

namespace Matthww\AutoInv;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class AutoInv extends PluginBase implements Listener
{

    public function onEnable()
    {
        $serverName = $this->getServer()->getName();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if ($serverName === "PMMP" or $serverName === "PocketMine-MP") {
            $this->getLogger()->info("is enabled!");
        } else {
            $this->getLogger()->warning("This plugin is not tested for " . $serverName);
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("is disabled!");
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $inventory = $player->getInventory();

        if ($event->isCancelled() == false) {
            if ($player->getGamemode() == 0) {
                if ($inventory->canAddItem($event->getItem())) {
                    foreach ($event->getDrops() as $drop) {
                        $inventory->addItem($drop);
                    }
                    $event->setDrops([]);
                } else {
                    $player->addTitle("§cYour inventory is full!", " ", 5, 40, 5);
                }
            }
        }
    }
}
