@props(['user'])

<div {{ $attributes }} x-data="{
    following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
    followersCount: {{ $user->followers()->count() }},
    follow() { 
        axios.post('/follow/{{ $user->id }}')
            .then( res => {
                this.following = !this.following
                this.followersCount = res.data.followersCount
            })
            .catch( err => {
                console.log(err)
            })
    }
}">
{{ $slot }}
</div>