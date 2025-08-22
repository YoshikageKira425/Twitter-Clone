import axios from 'axios';
import { useEffect, useState } from 'react';
import { FaBookmark, FaHeart, FaRegBookmark, FaRegComment, FaRegHeart, FaRetweet } from 'react-icons/fa';

export default function Comment({ comment, is_inside_tweet = true }) {
    const [like, setLike] = useState(false);
    const [retweet, setRetweet] = useState(false);
    const [bookmark, setBookmark] = useState(false);
    const [commentCount, setCommentCount] = useState(0);
    const [likeCount, setLikeCount] = useState(0);
    const [retweetCount, setRetweetCount] = useState(0);

    useEffect(() => {
        setLike(comment.is_liked_by_user || false);
        setRetweet(comment.is_retweeted_by_user || false);
        setBookmark(comment.is_bookmarked_by_user || false);
        setLikeCount(comment.likes_count || 0);
        setRetweetCount(comment.retweets_count || 0);
        setCommentCount(comment.comments_count || 0);
    }, [comment]);

    const formatTimestamp = (date) => {
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
            await axios.post(`/api/comment/${comment.id}/like`, { _method: 'DELETE' });

            setLikeCount(likeCount - 1);
        } else {
            await axios.post(`/api/comment/${comment.id}/like`);

            setLikeCount(likeCount + 1);
        }

        setLike(!like);
    };
    const retweetClick = async () => {
        if (retweet) {
            await axios.post(`/api/comment/${comment.id}/retweet`, { _method: 'DELETE' });
            setRetweetCount(retweetCount - 1);
        } else {
            await axios.post(`/api/comment/${comment.id}/retweet`);
            setRetweetCount(retweetCount + 1);
        }

        setRetweet(!retweet);
    };
    const bookmarkClick = async () => {
        if (bookmark) await axios.post(`/api/comment/${comment.id}/bookmark`, { _method: 'DELETE' });
        else await axios.post(`/api/comment/${comment.id}/bookmark`);

        setBookmark(!bookmark);
    };

    return (
        <div className="flex border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <img className="mr-3 h-10 w-10 rounded-full" src={comment.user.profile_image} alt="User avatar" />

            <div className="flex-grow">
                <div className="flex items-center justify-between">
                    <div className="flex items-center">
                        <span className="font-bold text-white">{comment.user.name}</span>
                        <span className="mx-1 text-gray-500">·</span>
                        <span className="text-gray-500">{formatTimestamp(comment.created_at)}</span>
                    </div>
                </div>

                <p className="mt-1 text-white">{comment.content}</p>

                <div className="mt-3 flex space-x-12 text-gray-500">
                    <a href={`/comment/${comment.id}`} className="flex cursor-pointer items-center space-x-2 hover:text-blue-500">
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
    );
}
