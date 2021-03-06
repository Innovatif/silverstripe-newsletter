<?php

/**
 * Displays a list of all members that have unsubscribed from the list
 *
 * @package newsletter
 */
class UnsubscribedList extends FormField {

    protected $nlType;

    function __construct( $name, $newsletterType ) {
        parent::__construct( $name, '', null );

        if( is_object( $newsletterType ) )
            $this->nlType = $newsletterType;
        else
            $this->nlType = DataObject::get_by_id( 'NewsletterType', $newsletterType );
    }

    function FieldHolder() {
        return $this->renderWith( 'NewsletterAdmin_UnsubscribedList' );
    }

    function Entries() {

        $id = $this->nlType->ID;

        if(defined('DB::USE_ANSI_SQL')) {
        	$unsubscribeRecords = DataObject::get('UnsubscribeRecord', "\"NewsletterTypeID\"='$id'" );
        } else {
			$unsubscribeRecords = DataObject::get('UnsubscribeRecord', "`NewsletterTypeID`='$id'" );
        }


        // user_error($id, E_USER_ERROR );

        if( !$unsubscribeRecords )
            return null;

        foreach( $unsubscribeRecords as $unsubscribeRecord ) {
			if( $unsubscribeRecord ) {
				$unsubscribedUsers[] = new ArrayData( array(
                    'Record' => $unsubscribeRecord,
                    'Member' => DataObject::get_by_id( 'Member', $unsubscribeRecord->MemberID )
                ));
            }
        }

        return new DataObjectSet( $unsubscribedUsers );
    }

    function setController($controller) {
		$this->controller = $controller;
	}
}