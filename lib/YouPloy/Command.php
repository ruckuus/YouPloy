<?php
namespace YouPloy;

class Command {
  public function takeServerOutOfRotationCommand($session) {
    
    return true;
  }

  public function doDeployArtefactCommand($session) {
    return true;
  }

  public function getFeedbackCommand($session) {
    return true;
  }
}
