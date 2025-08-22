import axios from 'axios';
import { useEffect, useState } from 'react';
import { FaBookmark, FaHeart, FaRegBookmark, FaRegComment, FaRegHeart, FaRetweet } from 'react-icons/fa';

export default function Tweet({ tweet }) {
    const [like, setLike] = useState(false);
    const [retweet, setRetweet] = useState(false);
    const [bookmark, setBookmark] = useState(false);
    const [commentCount, setCommentCount] = useState(0);
    const [likeCount, setLikeCount] = useState(0);
    const [retweetCount, setRetweetCount] = useState(0);

    useEffect(() => {
        setLike(tweet.is_liked_by_user || false);
        setRetweet(tweet.is_retweeted_by_user || false);
        setBookmark(tweet.is_bookmarked_by_user || false);
        setLikeCount(tweet.likes_count || 0);
        setRetweetCount(tweet.retweets_count || 0);
        setCommentCount(tweet.comments_count || 0);
    }, [tweet]);

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

    const likeClick = async () => {
        if (like) {
            await axios.post(`/api/tweets/${tweet.id}/like`, { _method: 'DELETE' });
            setLikeCount(likeCount - 1);
        } else {
            await axios.post(`/api/tweets/${tweet.id}/like`);
            setLikeCount(likeCount + 1);
        }

        setLike(!like);
    };
    const retweetClick = async () => {
        if (retweet) {
            await axios.post(`/api/tweets/${tweet.id}/retweet`, { _method: 'DELETE' });
            setRetweetCount(retweetCount - 1);
        } else {
            await axios.post(`/api/tweets/${tweet.id}/retweet`);
            setRetweetCount(retweetCount + 1);
        }

        setRetweet(!retweet);
    };
    const bookmarkClick = async () => {
        if (bookmark) await axios.post(`/api/tweets/${tweet.id}/bookmark`, { _method: 'DELETE' });
        else await axios.post(`/api/tweets/${tweet.id}/bookmark`);

        setBookmark(!bookmark);
    };

    return (
        <div className="cursor-pointer border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <div className="flex">
                <a href={`/account/${tweet.user.name}`}>
                    <img className="mr-3 h-10 w-10 rounded-full" src={tweet.user.profile_image} alt="User avatar" />
                </a>
                <div className="flex flex-grow flex-col">
                    <a href={`/tweet/${tweet.id}`}>
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
                    </a>

                    <div className="mt-3 flex space-x-12 text-gray-500">
                        <a href={`/tweet/${tweet.id}`} className="flex cursor-pointer items-center space-x-2 hover:text-blue-500">
                            <FaRegComment className="h-4 w-4" />
                            <span className="text-sm">{commentCount}</span>
                        </a>
                        <button
                            onClick={retweetClick}
                            className={`flex cursor-pointer items-center space-x-2 ${!retweet ? 'hover:text-green-500' : 'text-green-500 hover:text-green-800'}`}
                        >
                            <FaRetweet className="h-4 w-4" />
                            <span className="text-sm">{retweetCount}</span>
                        </button>
                        <button
                            onClick={likeClick}
                            className={`flex cursor-pointer items-center space-x-2 ${!like ? 'hover:text-red-500' : 'text-red-500 hover:text-red-800'}`}
                        >
                            {!like ? <FaRegHeart className="h-4 w-4" /> : <FaHeart className="h-4 w-4" />}
                            <span className="text-sm">{likeCount}</span>
                        </button>
                        <button
                            onClick={bookmarkClick}
                            className={`flex cursor-pointer items-center space-x-2 text-white hover:text-neutral-600`}
                        >
                            {!bookmark ? <FaRegBookmark className="h-4 w-4" /> : <FaBookmark className="h-4 w-4" />}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
