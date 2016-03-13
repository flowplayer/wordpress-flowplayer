<?php

interface Flowplayer5_IFlowplayer {

	public function get_player_info();
	public function enqueue_scripts( array $args );
	public function enqueue_styles( array $args );
	public function render_video( Flowplayer5_IVideo $video, array $args );
	public function render_playlist( Flowplayer5_IPlaylist $video, array $args );

	// [ ... ]
}