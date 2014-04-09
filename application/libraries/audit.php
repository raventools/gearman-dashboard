<?php

class Audit {
	public function Log() {
		error_log(json_encode(func_get_args()));
	}
}
