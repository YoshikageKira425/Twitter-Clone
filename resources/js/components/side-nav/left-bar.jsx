import axios from 'axios';
import { useEffect, useState } from 'react';
import Hashtag from '@/components/side-nav/hashtag';

export default function LeftBar() {
    const [hashtags, setHashtags] = useState([]);

    useEffect(() => {
        axios
            .get('/api/hashtags')
            .then((response) => {
                setHashtags(response.data.hashtags);
            })
            .catch((error) => {
                console.error('Error fetching hashtags:', error);
            });
    }, []);

    return (
        <div className="p-4">
            <h3 className="text-xl font-bold">What's happening</h3>
            {hashtags.length > 0 && hashtags.map((hashtag, index) => <Hashtag key={index} name={hashtag.name} usage_count={hashtag.tweets_count} />)}
        </div>
    );
}


