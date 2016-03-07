<?php

	/**
	 * Milestone calendar event context implementation
	 *
	 * @package activeCollab.modules.system
	 * @subpackage models
	 */
	class IMilestoneCalendarEventContextImplementation extends ICalendarEventContextImplementation {

		/**
		 * Construct calendar event context helper instance
		 *
		 * @param Milestone $object
		 * @throws InvalidInstanceError
		 */
		function __construct(Milestone $object) {
			if($object instanceof Milestone) {
				parent::__construct($object);
			} else {
				throw new InvalidInstanceError('object', $object, 'Milestone');
			} // if
		} // __construct

		/**
		 * Describe object as calendar event
		 *
		 * @param IUser $user
		 * @param bool $detailed
		 * @param bool $for_interface
		 * @param int $min_state
		 * @return mixed
		 */
		function describe(IUser $user, $detailed = false, $for_interface = false, $min_state = STATE_VISIBLE) {
			$result = array(
				'id'            => $this->object->getId(),
				'type'          => 'Milestone',
				'parent_id'     => $this->object->getProjectId(),
				'parent_type'   => 'Project',
				'name'          => $this->object->getName(),
				'ends_on'       => $this->object->getDueOn(),
				'starts_on'     => $this->object->getStartOn(),
				'permissions'   => array(
					'can_edit'        => false,
					'can_trash'       => false,
					'can_reschedule'  => true
				),
				'urls'          => array(
					'view'          => $this->object->getViewUrl(),
					'edit'          => $this->object->getEditUrl(),
					'reschedule'    => $this->object->schedule()->getRescheduleUrl()
				)
			);

			return $result;
		} // describe

	}