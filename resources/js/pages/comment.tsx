import NavBar from '@/components/side-nav/nav-bar.jsx';
import Tweet from '@/components/tweets/tweet';
import CommentSection from '@/components/tweets/commentSection';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import Comment from '@/components/tweets/comment';

export default function Home() {
    const { auth, current_comment, comments, tweet } = usePage<SharedData>().props;
    const [comment, setComment] = useState('');
    const [allComments, setAllComments] = useState(comments);

    console.log(current_comment);
    console.log(comments);

    const postComment = async () => {
        try {
            const response = await axios.post('/api/comments', { content: comment, commentable_id: current_comment.id, commentable_type: "comments" });

            setAllComments(prevComments => [response.data.comment, ...prevComments]);

            setComment('');
            alert("The comment was sent!!!");
        } catch (error) {
            console.error('Error posting tweet:', error);
            alert('There was error.');
        }
    };

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r border-gray-800 bg-black">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r border-gray-800 bg-black">
                <Tweet tweet={tweet} />
        
                <div className='mt-20 border-b border-gray-800'></div>

                <Comment comment={current_comment} />

                <div className="flex border-b border-gray-800 p-4">
                    <div className="flex w-full">
                        <textarea onChange={(e) => setComment(e.target.value)} value={comment}
                            className="h-12 w-full resize-none bg-transparent p-2 text-lg placeholder-gray-500 outline-none"
                            placeholder="Leave Comment!"
                        />
                        <button onClick={postComment} className="rounded-full bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-600">
                            Post
                        </button>
                    </div>
                </div>

                <CommentSection comments={allComments} />
            </div>

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto border-l border-gray-800 bg-black">
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
