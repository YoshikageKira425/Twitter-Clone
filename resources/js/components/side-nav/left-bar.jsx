import axios from 'axios';
import { useEffect, useState } from 'react';

export default function LeftBar() {
    const [hashtags, setHashtags] = useState([]);
    console.log(hashtags);

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
            {hashtags.length > 0 &&
                hashtags.map((hashtag) => (Hashtag(hashtag)))
            }
        </div>
    );
}

function Hashtag({ name, usage_count }) {
    return (
        <div className="mt-4 rounded-xl bg-gray-900 p-4">
            <p className="text-gray-400">#{name}</p>
            <p className="text-sm text-gray-500">{usage_count} posts</p>
        </div>
    );
}
