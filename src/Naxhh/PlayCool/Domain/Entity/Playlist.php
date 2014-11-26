<?php

namespace Naxhh\Playcool\Domain\Entity;

use Naxhh\Playcool\Domain\Adapter\ArrayCollection;
use Naxhh\Playcool\Domain\Exception\TrackAlreadyAddedException;

class Playlist
{
    /**
     * The name of the playlist.
     *
     * @var string
     */
    private $name;

    /**
     * List of tracks of the current playlist.
     *
     * @var Track[]
     */
    private $tracks;

    /**
     * Public interface for creating a new playlist.
     *
     * @param  string $playlist_name The name of the playlist
     * @return Playlist
     */
    public static function create($playlist_name) {
        return new self($playlist_name);
    }

    private function __construct($name) {
        $this->name   = $name;
        $this->tracks = new ArrayCollection;
    }

    /**
     * Get the list of tracks for the playlist.
     *
     * @return Track[]
     */
    public function getTracks() {
        return $this->tracks;
    }

    /**
     * Adds a track into the playlist. It fails if the track is already in the playlist.
     *
     * @param string $track_name The name of the track to add.
     * @return void
     * @throws Exception\TrackAlreadyAddedException If the track is already in the playlist.
     */
    public function addTrack($track_name) {
        $track = Track::create($track_name);

        $track_already_exists = function($key, $item) use ($track) {
            return $track->getName() === $item->getName();
        };

        if ($this->tracks->exists($track_already_exists)) {
            throw new TrackAlreadyAddedException(sprintf(
                'Track with name "%s" already exist in the playlist', $track->getName()
            ));
        }

        $this->tracks->set($track->getName(), $track);
    }

    /**
     * Removes a track from the playlist.
     *
     * @param string $track_name The name of the track.
     * @return void
     */
    public function removeTrack($track_name) {
        $this->tracks->remove($track_name);
    }
}