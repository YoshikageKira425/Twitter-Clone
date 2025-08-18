import { FaHeart, FaRetweet, FaUserPlus, FaBookmark } from 'react-icons/fa';
import { MdAlternateEmail } from 'react-icons/md';

export default function NotificationItem({ notification }) {
    const { type, user, data, created_at } = notification;

    let icon, message;

    const formatTimestamp = (date) => {
        const now = new Date();
        const notificationDate = new Date(date);
        const diffInMinutes = Math.floor((now - notificationDate) / (1000 * 60));

        if (diffInMinutes < 60) {
            return `${diffInMinutes}m`;
        }
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) {
            return `${diffInHours}h`;
        }
        const diffInDays = Math.floor(diffInHours / 24);
        return `${diffInDays}d`;
    };

    switch (type) {
        case 'like':
            icon = <FaHeart className='text-red-500' />;
            message = (
                <p className="mt-1">
                    <span className="font-bold">{user.name}</span> liked your post.
                </p>
            );
            break;
        case 'retweet':
            icon = <FaRetweet className='text-green-500' />;
            message = ( 
                <p className="mt-1">
                    <span className="font-bold">{user.name}</span> retweeted your post.
                </p>
            );
            break;
        case 'bookmark':
            icon = <FaBookmark className='text-white' />;
            message = (
                <p className="mt-1">
                    <span className="font-bold">{user.name}</span> bookmark your post.
                </p>
            );
            break;
        case 'mention':
            icon = <MdAlternateEmail className='text-blue-500' />;
            message = (
                <p className="mt-1">
                    <span className="font-bold">{user.name}</span> mentioned you.
                </p>
            );
            break;
        case 'follow':
            icon = <FaUserPlus className='text-green-500' />;
            message = (
                <p className="mt-1">
                    <span className="font-bold">{user.name}</span> followed you.
                </p>
            );
            break;
        default:
            icon = null;
            message = null;
    }

    return (
        <div className="flex border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <div className="mr-4 flex-shrink-0">{icon}</div>
            <div className="flex-grow">
                <div className="flex items-center">
                    <img className="mr-3 h-10 w-10 rounded-full" src={user.profile_image} alt="User avatar" />
                    <div>
                        {message}
                        {data && <p className="mt-1 text-gray-500">{data}</p>}
                    </div>
                </div>
                <span className="mt-2 block text-right text-sm text-gray-500">{formatTimestamp(created_at)}</span>
            </div>
        </div>
    );
}
