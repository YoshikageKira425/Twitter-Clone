import { BiDotsHorizontalRounded } from 'react-icons/bi';
import { FaRegComment, FaRegHeart, FaRetweet, FaRegBookmark } from 'react-icons/fa';

export default function Tweet({ tweet }) {
    console.log(tweet);
    const timeAgo = (date) => {
        const now = new Date();
        const commentDate = new Date(date);
        const diffInMinutes = Math.floor((now - commentDate) / (1000 * 60));

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

    const like = () => {};
    const retweet = () => {};
    const bookmark = () => {};

    return (
        <div className="cursor-pointer border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <div href={`/tweet/${tweet.id}`} className="flex">
                <a href={`/account/${tweet.user.name}`}>
                    <img className="mr-3 h-10 w-10 rounded-full" src={tweet.user.profile_image} alt="User avatar" />
                </a>
                <a href={`/tweet/${tweet.id}`} className="flex flex-grow flex-col">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <span className="font-bold text-white">{tweet.user.name}</span>
                            <span className="mx-1 text-gray-500">·</span>
                            <span className="text-gray-500">{timeAgo(tweet.created_at)}</span>
                        </div>
                    </div>

                    <p className="mt-1 text-white">{tweet.content}</p>

                    {tweet.image && (
                        <div className="my-3">
                            <img src={tweet.image} className="w-150 rounded-2xl" alt="Tweet content" />
                        </div>
                    )}

                    <div className="mt-3 flex space-x-12 text-gray-500">
                        <button onClick={like} className="flex cursor-pointer items-center space-x-2 hover:text-blue-500">
                            <FaRegComment className="h-4 w-4" />
                            <span className="text-sm">{tweet.comments_count}</span>
                        </button>
                        <button onClick={retweet} className="flex cursor-pointer items-center space-x-2 hover:text-green-500">
                            <FaRetweet className="h-4 w-4" />
                            <span className="text-sm">{tweet.retweets_count}</span>
                        </button>
                        <button onClick={like} className="flex cursor-pointer items-center space-x-2 hover:text-red-500">
                            <FaRegHeart className="h-4 w-4" />
                            <span className="text-sm">{tweet.likes_count}</span>
                        </button>
                        <button onClick={bookmark} className="flex cursor-pointer items-center space-x-2 hover:text-neutral-600">
                            <FaRegBookmark className="h-4 w-4" />
                            <span className="text-sm">{tweet.bookmarks_count}</span>
                        </button>
                    </div>
                </a>
            </div>
        </div>
    );
}
