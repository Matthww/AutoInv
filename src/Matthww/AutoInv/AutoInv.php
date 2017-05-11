<?php

namespace Matthww\AutoInv;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\Player;

class AutoInv extends PluginBase implements Listener {

    public function onEnable()
    {
        $serverName = $this->getServer()->getName();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if($serverName === "PMMP" or $serverName === "PocketMine-MP")
        {
            $this->getLogger()->info("is enabled!");
        }
        else{
            $this->getLogger()->warning("This plugin is not tested for " . $serverName);
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("is disabled!");
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();

        foreach($event->getDrops() as $drop)
        {
            $itemId = $drop->getId();

            if($this->isInventoryFull($player) == true)
            {
                $player->addTitle("§cYour inventory is full!", "");
            } else {
                if($player->getGamemode() == 0)
                {
                    if($event->isCancelled() == false)
                    {
                        $player->getInventory()->addItem(Item::get($itemId));
                        $event->setDrops([]);
                    }
                }
            }
        }
    }

    public function isInventoryFull(Player $player)
    {
        for($item = 0; $item < $player->getInventory()->getSize(); $item++)
        {
            if($player->getInventory()->getItem($item)->getId() === 0)
            {
                return false;
            }
        }
        return true;
    }
}
