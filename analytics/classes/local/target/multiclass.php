<?php

namespace core_analytics\local\target;

defined('MOODLE_INTERNAL') || die();

/**
 * Multi-class classifier target.
 *
 * @package   core_analytics
 * @copyright 2019 Apetrei Vlad
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class multiclass extends discrete {

    /**
     * is_linear
     *
     * @return bool
     */
    public function is_linear() {
        return false;
    }

    /**
     * Returns the target discrete values.
     *
     * Only useful for targets using discrete values, must be overwriten if it is the case.
     *
     * @return array
     */
    public static final function get_classes() {
        return array(0, 1, 2);
    }

    /**
     * Returns the predicted classes that will be ignored.
     *
     * @return array
     */
    protected function ignored_predicted_classes() {
        // Zero-value class is usually ignored in binary classifiers.
        return array(0);
    }

    /**
     * Is the calculated value a positive outcome of this target?
     *
     * @param string $value
     * @param string $ignoredsubtype
     * @return int
     */
    public function get_calculation_outcome($value, $ignoredsubtype = false) {

        if (!self::is_a_class($value)) {
            throw new \moodle_exception('errorpredictionformat', 'analytics');
        }

        if (in_array($value, $this->ignored_predicted_classes(), false)) {
            // Just in case, if it is ignored the prediction should not even be recorded but if it would, it is ignored now,
            // which should mean that is it nothing serious.
            return self::OUTCOME_VERY_POSITIVE;
        }

        // By default binaries are danger when prediction = 1.
        if ($value) {
            return self::OUTCOME_VERY_NEGATIVE;
        }
        return self::OUTCOME_VERY_POSITIVE;
    }

    /**
     * classes_description
     *
     * @return string[]
     */
    protected static function classes_description() {
        return array(
            get_string('first class'),
            get_string('second class'),
            get_string('third class')
        );
    }

}
