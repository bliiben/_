<?php
/**
* Error
*/
class Error
{
	private $message,$type;
	function __construct($message,$type="danger")
	{
		/*
			$type can be :
				success
				info
				warning
				danger
		*/
		$this->message = $message;
		$this->type = $type;
	}
	public function publishError()
	{
		?>
		<div class="alert alert-<?php echo $this->type; ?> alert-dismissable">
 			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  			<?php echo $this->message; ?>
		</div>
		<?php
	}
}
