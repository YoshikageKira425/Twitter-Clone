import NavBar from '@/components/side-nav/nav-bar.jsx';
import NotificationItem from '@/components/NotificationItem';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export default function Home() {
    const { auth } = usePage<SharedData>().props;

    const notifications = [
        {
            type: 'like',
            user: { name: 'Alice', username: 'alice_1', profile_image_url: 'https://via.placeholder.com/150' },
            tweet_content: 'Just finished building my first React component! So proud. #react #coding',
            created_at: new Date(Date.now() - 3600000).toISOString(), 
        },
        {
            type: 'retweet',
            user: { name: 'Bob', username: 'bob_dev', profile_image_url: 'https://via.placeholder.com/150' },
            tweet_content: 'My new blog post is live! Check it out!',
            created_at: new Date(Date.now() - 7200000).toISOString(),
        },
        {
            type: 'mention',
            user: { name: 'Charlie', username: 'charlie_ux', profile_image_url: 'https://via.placeholder.com/150' },
            tweet_content: '@You Can you take a look at the new design mockups?',
            created_at: new Date(Date.now() - 10800000).toISOString(), 
        },
        {
            type: 'follow',
            user: { name: 'David', username: 'david_data', profile_image_url: 'https://via.placeholder.com/150' },
            created_at: new Date(Date.now() - 14400000).toISOString(), 
        },
    ];

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="w-1/5 border-r border-gray-800">
                <NavBar />
            </div>

            <div className="w-3/5 flex-2 border-r border-gray-800">
                {notifications.map((notification, index) => (
                    <NotificationItem key={index} notification={notification} />
                ))}
            </div>

            <div className="w-1/4 border-l border-gray-800">
                <div className="p-4">
                    <h3 className="text-xl font-bold">What's happening</h3>
                    <div className="mt-4 rounded-xl bg-gray-900 p-4">
                        <p className="text-gray-400">#Topic1</p>
                        <p className="text-sm text-gray-500">2.5K posts</p>
                    </div>
                </div>
            </div>
        </div>
    );
}
