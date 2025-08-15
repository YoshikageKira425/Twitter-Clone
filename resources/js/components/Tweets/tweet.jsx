import React from 'react';
import { BiDotsHorizontalRounded } from 'react-icons/bi'; 
import { FaRegComment, FaRetweet, FaRegHeart, FaRegChartBar } from 'react-icons/fa';

export default function Tweet({ tweet }) {
    const timeAgo = (date) => {
        const now = new Date();
        const tweetDate = new Date(date);
        const diffInHours = Math.floor((now - tweetDate) / (1000 * 60 * 60));
        return `${diffInHours}h`;
    };

    return (
        <div className="cursor-pointer border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <div className="flex">
                <img
                    className="mr-3 h-10 w-10 rounded-full"
                    src={tweet.user.profile_image_url || 'https://via.placeholder.com/150'}
                    alt="User avatar"
                />
                <div className="flex flex-col flex-grow">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <span className="font-bold text-white">{tweet.user.name}</span>
                            <span className="ml-2 text-gray-500">@{tweet.user.username}</span>
                            <span className="mx-1 text-gray-500">·</span>
                            <span className="text-gray-500">{timeAgo(tweet.created_at)}</span>
                        </div>
                        <BiDotsHorizontalRounded className="h-4 w-4 text-gray-500 cursor-pointer hover:bg-gray-700 rounded-full" />
                    </div>

                    <p className="mt-1 text-white">{tweet.content}</p>

                    {tweet.image && (
                        <div className="my-3">
                            <img src={tweet.image} className="w-150 rounded-2xl" alt="Tweet content" />
                        </div>
                    )}

                    <div className="flex justify-between text-gray-500 mt-3">
                        <div className="flex items-center space-x-2">
                            <FaRegComment className="h-4 w-4" />
                            <span>{0}</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <FaRetweet className="h-4 w-4" />
                            <span>{0}</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <FaRegHeart className="h-4 w-4" />
                            <span>{0}</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <FaRegChartBar className="h-4 w-4" />
                            <span>{0}</span>
                        </div>
                        <div className="flex items-center space-x-2">
                            <svg className="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                <g>
                                    <path d="M17.5 12c0 .28-.22 1.5-.5 1.5-1.12.55-2.24 1.1-3.36 1.65-1.1.55-2.2 1.1-3.3 1.65-.96.48-1.92.96-2.88 1.44-1.2.6-2.4 1.2-3.6 1.8-1.12.56-2.24 1.12-3.36 1.68-.86.43-1.72.86-2.58 1.29l-.02.01c-.14-.04-.2-.25-.2-.5-1.25-1.7-2.5-3.4-3.75-5.1-1.25-1.7-2.5-3.4-3.75-5.1-.37-.5-1.24-1.68-1.5-1.5h-1c-.28 0-.5.22-.5.5v1.25h1.5c.28 0 .5-.22.5-.5v-1.25h-1.5c-.28 0-.5-.22-.5-.5z"></path>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}