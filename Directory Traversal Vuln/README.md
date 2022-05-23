## Người thực hiện: Trần Ngọc Nam
## Thời gian thực hiện: 23/5/2022

# Mục lục:
  - [Directory Traversal:](#directory-traversal)
  - [Ví dụ về cách tấn công:](#ví-dụ-về-cách-tấn-công)
  - [Một số cách ngăn chặn:](#một-số-cách-ngăn-chặn)

## Directory Traversal:
- Directory traversal( hay còn gọi là Path traversal) là một lỗ hổng web cho phép kẻ tấn công đọc các file không mong muốn trên server.
- Nó dẫn đến việc bị lộ thông tin nhạy cảm của ứng dụng như thông tin đăng nhập , một số file hoặc thư mục của hệ điều hành.
- Trong một số trường hợp cũng có thể ghi vào các files trên server, cho phép kẻ tấn công có thể thay đổi dữ liệu hay thậm chí là chiếm quyền điều khiển server.

## Ví dụ về cách tấn công:
- Một ứng dụng load ảnh như sau
  ```php
  <img src="/?filename=test_image.png">
  ```
- Khi chúng ta gửi một request với một param filename=test_image.png thì sẽ trả về nội dung của file được chỉ định với tệp hình ảnh ở <code>/var/www/images/test_image.png</code>.
- Ứng dụng không thực hiện việc phòng thủ cuộc tấn công path traversal, kẻ tấn công có thể thực hiện một yêu cầu tùy ý để có thể đọc các file trong hệ thống. Ví dụ <code>https://hostname.abc/?filename=../../../etc/passwd</code>.
  - Khi đó ứng dụng sẽ đọc file với đường dẫn là <code>/var/www/images/../../../etc/passwd</code> với mỗi <code>../</code> là trở về thư mục cha của thư mục hiện tại. Như vậy với <code>../../../</code> thì từ thư mục <code>/var/www/images/</code> đã trở về thư mục gốc và file <code>/etc/passwd</code> chính là file được đọc.
  - Trên các hệ điều hành dựa trên Unix thì <code>/etc/passwd/</code> là một file chứa thông tin về các người dùng.
  - Trên Windows thì có thể dùng cả hai <code>../</code> và <code>..\ </code> để thực hiện việc tấn công này.

## Một số cách ngăn chặn:
- Nên validate input của người dùng trước khi xử lý nó.
- Sử dụng whitelist cho những giá trị được cho phép.
- Hoặc tên file là những kí tự số,chữ không nên chứa những ký tự đặc biệt.