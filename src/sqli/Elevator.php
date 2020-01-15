<?php
declare(strict_types=1);

namespace Sqli;
/**
 * Class Elevator
 * @package Sqli
 */
class Elevator
{
    private $elevatorId; //The elevator id
    private $currentFloor; //The current floor where the elevator is
    private $state; //The current state of the elevator

    /**
     * Elevator constructor.
     *
     * @param string $id
     * @param int $floor
     * @param string $state
     */
    function __construct($id, $floor, $state = "READY")
    {
        $this->elevatorId = $id;
        $this->currentFloor = $floor;
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getElevatorId(): string
    {
        return $this->elevatorId;
    }

    /**
     * @return int
     */
    public function getCurrentFloor(): int
    {
        return $this->currentFloor;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Elevator
     */
    public function setState(string $state): Elevator
    {
        if (!in_array($state, array("DOWN", "UP", "STOPPED"))) {
            $this->state = "READY";
        } else {
            $this->state = $state;
        }
        return $this;
    }

    /**
     * @param int $currentFloor
     */
    public function setCurrentFloor(int $currentFloor): void
    {
        $this->currentFloor = $currentFloor;
    }


}