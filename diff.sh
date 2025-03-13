#!/bin/bash

# 获取脚本所在目录
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"


# 确保目标目录存在
TARGET_DIR="$SCRIPT_DIR/diff-data"
if [ ! -d "$TARGET_DIR" ]; then
  echo "Creating directory: $TARGET_DIR" >> "$LOG_FILE"
  mkdir -p "$TARGET_DIR"
fi

# 定义变量
FROM_COMMIT="8f157193" # 不包含这次提交
TO_COMMIT="37d4f9c6"
ARCHIVE_NAME="$TARGET_DIR/upgrade_package.tar.gz"
DELETE_LIST="$TARGET_DIR/delete_list.txt"
LOG_FILE="$TARGET_DIR/upgrade_log.txt"
ALL_CHANGED_FILE="$TARGET_DIR/all_changed_files.txt"


# 清空或创建日志文件
> "$LOG_FILE"
> "$DELETE_LIST"
> "$ALL_CHANGED_FILE"

# 获取两次提交之间的所有提交哈希
commit_range=$(git rev-list --reverse $FROM_COMMIT..$TO_COMMIT)

# 用于存储所有改动的文件（去重）
all_changed_files=()

# 遍历所有提交
echo "$commit_range" | while read -r commit; do
  # 获取当前提交的改动文件列表及状态
  git show --name-status --pretty=format: $commit | while IFS=$'\t' read -r status file; do
    # 记录所有文件的操作
    echo "Processing file: $file with status: $status in commit: $commit" >> "$LOG_FILE"

    case $status in
      A|M)
        # 文件存在且已被添加或修改，尝试添加到归档
        if [ -e "$file" ]; then
          # 使用数组去重
          if [[ ! " ${all_changed_files[@]} " =~ " ${file} " ]]; then
            all_changed_files+=("$file")
            echo "$file" >> "$ALL_CHANGED_FILE"
          fi
        else
          echo "File not found: $file in commit: $commit" >> "$LOG_FILE"
        fi
        ;;
      D)
        # 文件被删除，记录到删除列表
        echo "$file" >> "$DELETE_LIST"
        echo "Marked for deletion: $file in commit: $commit" >> "$LOG_FILE"
        ;;
      *)
        # 其他情况，可能是符号链接或其他特殊文件，记录到日志
        echo "Unhandled status for file: $file, status: $status in commit: $commit" >> "$LOG_FILE"
        ;;
    esac
  done
done

# 创建临时归档文件
tar --ignore-failed-read -czf "$SCRIPT_DIR/temp_upgrade.tar.gz" -T "$ALL_CHANGED_FILE" 2>> "$LOG_FILE"

# 将删除列表也添加到归档中
echo "Adding delete list to archive" >> "$LOG_FILE"
tar --ignore-failed-read -rf "$SCRIPT_DIR/temp_upgrade.tar.gz" "$DELETE_LIST" 2>> "$LOG_FILE"

# 移动临时归档为最终的升级包
mv "$SCRIPT_DIR/temp_upgrade.tar.gz" "$ARCHIVE_NAME"

echo "Upgrade package created: $ARCHIVE_NAME" >> "$LOG_FILE"
echo "Check $LOG_FILE for details and any errors."