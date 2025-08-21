import Comment from '@/components/tweets/comment';

export default function CommentSection({ comments }) {
    return (
        <>
            <div className="flex flex-col bg-black">
                {
                    comments.length > 0 && 
                    comments.map((comment, index) => (
                        <Comment comment={comment} key={index} />
                    ))
                }
            </div>
        </>
    );
}