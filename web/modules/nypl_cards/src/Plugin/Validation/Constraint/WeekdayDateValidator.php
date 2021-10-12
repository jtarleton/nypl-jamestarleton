<?php

namespace Drupal\nypl_cards\Plugin\Validation\Constraint;

use Drupal\Core\Entity\EntityPublishedInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the WeekdayDate constraint.
 */
class WeekdayDateValidator extends ConstraintValidator {


  private $default_tz = 'America/New_York';

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {

    $date = $value[0]->getValue()['value'];
    $label = $value[0]->getFieldDefinition()->getLabel();

    if ($this->isWeekend($date)) {
      $this->context->addViolation($constraint->needsValue, [
        '%field' => $label
      ]);
    }
  }

  public function isWeekend($date) {
    $default_tz = date_default_timezone_get();
    $tz = (!empty($default_tz))  ? $default_tz : $this->default_tz;
    $inputDate = \DateTime::createFromFormat("Y-m-d", $date, new \DateTimeZone($tz));
    return $inputDate->format('N') >= 6;
  }


  // For the current date
  public function isTodayWeekend() {
    $default_tz = date_default_timezone_get();
    $tz = (!empty($default_tz))  ? $default_tz : $this->default_tz;
    $currentDate = new \DateTime("now", $tz);
    return $currentDate->format('N') >= 6;
  }


}
